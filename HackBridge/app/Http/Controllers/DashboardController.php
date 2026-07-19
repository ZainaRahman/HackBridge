<?php

namespace App\Http\Controllers;

use App\Models\Hackathon;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('skills', 'teamMemberships');

        $totalProjects  = Project::count();
        $totalMembers   = User::count();
        $myApplications = $user->applications()->count();

        $hackathons = Hackathon::orderBy('deadline')
                               ->where('deadline', '>', now())
                               ->limit(3)
                               ->get();

        $projects = Project::with('owner')
                           ->where('status', 'recruiting')
                           ->latest()
                           ->limit(4)
                           ->get();

        $suggested = User::where('id', '!=', $user->id)
                         ->where('availability', 'open')
                         ->inRandomOrder()
                         ->limit(4)
                         ->get();
        
        $myWorkspaces = Project::where('owner_id', $user->id)
                               ->orWhereHas('applications', function ($q) use ($user) {
                                   $q->where('user_id', $user->id)->where('status', 'accepted');
                               })
                               ->withCount([
                                   'tasks as pending_tasks_count' => fn ($q) => $q->whereIn('status', ['todo', 'in_progress']),
                                   'ghostFlags as ghost_flags_count',
                               ])
                               ->get();

        return view('dashboard.index', compact(
            'user', 'hackathons', 'projects', 'suggested', 'myWorkspaces',
            'totalProjects', 'totalMembers', 'myApplications'
        ));
    }
}