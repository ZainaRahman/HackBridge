<?php
// FILE: app/Http/Controllers/WorkspaceController.php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\GhostFlag;
use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\SharedLink;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WorkspaceController extends Controller
{
    // Show workspace for a project
    public function show(Project $project)
    {
        // Only accepted members or owner can access
        if (!$this->canAccessProject($project)) {
            return redirect()->route('projects.show', $project->id)
                             ->with('error', 'You must be a team member to access the workspace.');
        }

        $isOwner = $project->owner_id === Auth::id();

        $tasks         = $project->tasks()->with('assignee', 'creator')->get();
        $announcements = $project->announcements()->with('author')->latest()->get();
        $links         = $project->sharedLinks()->with('user')->latest()->get();
        $files         = $project->files()->with('user')->latest()->get();
        $members       = $project->applications()
                                 ->where('status', 'accepted')
                                 ->with('user')
                                 ->get()
                                 ->pluck('user')
                                 ->push($project->owner)
                                 ->unique('id');
        $ghostFlags    = $project->ghostFlags()->with('flaggedUser')->get();

        $todo        = $tasks->where('status', 'todo');
        $in_progress = $tasks->where('status', 'in_progress');
        $done        = $tasks->where('status', 'done');

        return view('workspace.show', compact(
            'project', 'todo', 'in_progress', 'done',
            'announcements', 'links', 'files', 'members', 'ghostFlags', 'isOwner'
        ));
    }

    // Shared access check reused by resource routes below
    protected function canAccessProject(Project $project): bool
    {
        $isOwner  = $project->owner_id === Auth::id();
        $isMember = $project->applications()
                            ->where('user_id', Auth::id())
                            ->where('status', 'accepted')
                            ->exists();

        return $isOwner || $isMember;
    }

    // ── Tasks ──
    public function storeTask(Request $request, Project $project)
    {
        $request->validate([
            'title'    => 'required|string|max:120',
            'priority' => 'required|in:low,medium,high',
        ]);

        Task::create([
            'project_id'  => $project->id,
            'created_by'  => Auth::id(),
            'assigned_to' => $request->assigned_to ?: null,
            'title'       => $request->title,
            'description' => $request->description,
            'status'      => 'todo',
            'priority'    => $request->priority,
        ]);

        return back()->with('success', 'Task added!');
    }

    public function updateTask(Request $request, Task $task)
    {
        $task->update(['status' => $request->status]);
        return back()->with('success', 'Task updated!');
    }

    public function deleteTask(Task $task)
    {
        $task->delete();
        return back()->with('success', 'Task deleted.');
    }

    // ── Announcements ──
    public function storeAnnouncement(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'required|string|max:120',
            'body'  => 'required|string',
        ]);

        Announcement::create([
            'project_id' => $project->id,
            'user_id'    => Auth::id(),
            'title'      => $request->title,
            'body'       => $request->body,
        ]);

        return back()->with('success', 'Announcement posted!');
    }

    // ── Shared Links ──
    public function storeLink(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'url'   => 'required|url',
            'type'  => 'required|in:GitHub,Drive,Figma,Notion,Other',
        ]);

        SharedLink::create([
            'project_id' => $project->id,
            'user_id'    => Auth::id(),
            'title'      => $request->title,
            'url'        => $request->url,
            'type'       => $request->type,
        ]);

        return back()->with('success', 'Link added!');
    }

    public function deleteLink(SharedLink $link)
    {
        $link->delete();
        return back()->with('success', 'Link removed.');
    }

    // ── Resource Hub (file uploads) ──
    public function storeResource(Request $request, Project $project)
    {
        if (!$this->canAccessProject($project)) abort(403);

        $request->validate([
            'title' => 'required|string|max:100',
            'file'  => 'required|file|max:20480|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,csv,zip,rar,png,jpg,jpeg,gif,txt,md',
        ]);

        $uploaded = $request->file('file');
        $path     = $uploaded->store("resources/{$project->id}"); // stored on default (local) disk, not public

        ProjectFile::create([
            'project_id'    => $project->id,
            'user_id'       => Auth::id(),
            'title'         => $request->title,
            'original_name' => $uploaded->getClientOriginalName(),
            'path'          => $path,
            'mime_type'     => $uploaded->getClientMimeType(),
            'size'          => $uploaded->getSize(),
        ]);

        return back()->with('success', 'File uploaded!');
    }

    public function downloadResource(ProjectFile $resource)
    {
        if (!$this->canAccessProject($resource->project)) abort(403);

        if (!Storage::exists($resource->path)) {
            return back()->with('error', 'File no longer exists.');
        }

        return Storage::download($resource->path, $resource->original_name);
    }

    public function deleteResource(ProjectFile $resource)
    {
        $isOwner    = $resource->project->owner_id === Auth::id();
        $isUploader = $resource->user_id === Auth::id();

        if (!$isOwner && !$isUploader) abort(403);

        Storage::delete($resource->path);
        $resource->delete();

        return back()->with('success', 'File removed.');
    }

    // ── Ghost Flag ──
    public function flagGhost(Request $request, Project $project)
    {
        $request->validate([
            'flagged_user_id' => 'required|exists:users,id',
            'reason'          => 'nullable|string|max:300',
        ]);

        $exists = GhostFlag::where('project_id', $project->id)
                           ->where('flagged_user_id', $request->flagged_user_id)
                           ->where('flagged_by', Auth::id())
                           ->exists();

        if (!$exists) {
            GhostFlag::create([
                'project_id'      => $project->id,
                'flagged_user_id' => $request->flagged_user_id,
                'flagged_by'      => Auth::id(),
                'reason'          => $request->reason,
            ]);
        }

        return back()->with('success', 'Member flagged as inactive. The team leader has been notified.');
    }
}