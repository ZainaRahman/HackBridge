<?php

namespace App\Http\Controllers;

use App\Models\Hackathon;
use App\Models\Project;
use App\Models\ProjectApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with('owner', 'hackathon')->where('status', 'recruiting');

        if ($request->category) $query->where('category', $request->category);
        if ($request->dept)     $query->where('dept_preference', $request->dept);
        if ($request->search)   $query->where('title', 'like', '%'.$request->search.'%');

        $projects   = $query->latest()->paginate(9);
        $hackathons = Hackathon::orderBy('deadline')->get();

        return view('projects.index', compact('projects', 'hackathons'));
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
            'required_skills' => $request->required_skills ? explode(',', $request->required_skills) : [],
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
        $alreadyApplied = ProjectApplication::where('project_id', $project->id)
                                             ->where('user_id', Auth::id())
                                             ->exists();
        return view('projects.show', compact('project', 'alreadyApplied'));
    }

    public function apply(Request $request, Project $project)
    {
        $request->validate(['pitch' => 'required|string|min:20']);

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
        $application->update(['status' => $request->status]);
        return back()->with('success', 'Application ' . $request->status . '.');
    }
}