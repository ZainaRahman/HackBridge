<?php

namespace App\Http\Controllers;

use App\Models\Hackathon;
use App\Models\Project;
use App\Models\ProjectApplication;
use Illuminate\Http\Request;
use App\Models\Skill;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
   public function index(Request $request)
{
    $query = Project::with('owner', 'hackathon')->where('status', 'recruiting');

    if ($request->category) {
        $query->where('category', $request->category);
    }

    if ($request->dept && $request->dept !== 'Any') {
        $query->where(function ($q) use ($request) {
            $q->where('dept_preference', $request->dept)
              ->orWhere('dept_preference', 'Any');
        });
    }

    if ($request->search) {
        $query->where('title', 'like', '%'.$request->search.'%');
    }

    $projects   = $query->latest()->paginate(9);
    $hackathons = Hackathon::orderBy('deadline')->get();

    $category   = Project::whereNotNull('category')
                    ->pluck('category')
                    ->unique()
                    ->values();

    // Map skill IDs to skill names for rendering chips
    $skillsMap  = \App\Models\Skill::pluck('name', 'id');

    return view('projects.index', compact('projects', 'hackathons', 'category', 'skillsMap'));
}

    public function create()
    {
        $hackathons = Hackathon::where('deadline', '>', now())->orderBy('deadline')->get();
        return view('projects.create', compact('hackathons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'           => 'required|string|max:120',
            'description'     => 'required|string',
            'category'        => 'required|string',
            'team_size'       => 'required|integer|min:2|max:8',
            'dept_preference' => 'required|string',
        ]);

        Project::create([
            'owner_id'        => Auth::id(),
            'title'           => $request->title,
            'description'     => $request->description,
            'category'        => $request->category,
            'required_skills' => $request->required_skills
            ? collect(explode(',', $request->required_skills))
            ->map(fn ($s) => trim($s))
            ->filter()
            ->values()
            ->toArray()
            : [],
            'prerequisites'   => $request->prerequisites,
            'team_size'       => $request->team_size,
            'hackathon_id'    => $request->hackathon_id ?: null,
            'dept_preference' => $request->dept_preference,
            'deadline'        => $request->deadline,
            'status'          => 'recruiting',
        ]);

        return redirect()->route('projects.index')->with('success', 'Project posted successfully!');
    }

    public function show(Project $project)
{
    $project->load('owner.skills', 'applications.user', 'hackathon');

    // Extract skill IDs from the project cast array
    $skillIds = is_array($project->required_skills) ? $project->required_skills : [];

    // Fetch skill objects matching those IDs
    $skills = Skill::whereIn('id', $skillIds)->get();

    $alreadyApplied = ProjectApplication::where('project_id', $project->id)
                                         ->where('user_id', Auth::id())
                                         ->exists();

    return view('projects.show', compact('project', 'alreadyApplied', 'skills'));
}

    public function apply(Request $request, Project $project)
    {
        $request->validate(['pitch' => 'required|string|min:20']);

        if ($project->isFull()) {
            return back()->with('error', 'This team is already full.');
        }

        $already = ProjectApplication::where('project_id', $project->id)
                                      ->where('user_id', Auth::id())
                                      ->exists();
        if ($already) return back()->with('error', 'You already applied to this project.');

        ProjectApplication::create([
            'project_id' => $project->id,
            'user_id'    => Auth::id(),
            'pitch'      => $request->pitch,
            'status'     => 'pending',
        ]);

        return back()->with('success', 'Application sent! The team leader will review it.');
    }

    public function myProjects()
    {
        $projects = Project::with('applications', 'hackathon')
                           ->where('owner_id', Auth::id())
                           ->latest()->get();
        return view('projects.mine', compact('projects'));
    }

    public function updateApplication(Request $request, ProjectApplication $application)
    {
        if ($application->project->owner_id !== Auth::id()) abort(403);

        $request->validate(['status' => 'required|in:accepted,rejected']);

        if ($request->status === 'accepted' && $application->project->isFull()) {
            return back()->with('error', 'This team is already full — reject or reassign another member first.');
        }

        $application->update(['status' => $request->status]);

        // Once the team reaches its target size, close recruiting automatically
        // so the project drops off the open-projects listing and stops accepting
        // new applications. Only flips 'recruiting' -> 'in_progress' (a valid enum
        // value per the projects migration); never touches 'completed' or any
        // manually-set status.
        if ($request->status === 'accepted') {
            $project = $application->project;
            if ($project->status === 'recruiting' && $project->isFull()) {
                $project->update(['status' => 'in_progress']);
            }
        }

        return back()->with('success', 'Application ' . $request->status . '.');
    }
}