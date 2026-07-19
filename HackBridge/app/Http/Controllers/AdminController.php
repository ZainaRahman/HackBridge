<?php
// FILE: app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\AchievementMember;
use App\Models\Hackathon;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // NOTE: the 'admin' middleware is already applied at the route-group level
    // in web.php via Route::middleware(['auth', 'admin'])->prefix('admin')->...
    // Laravel 11+ removed $this->middleware() from the base Controller class,
    // so a constructor calling it here would throw — no need for it anyway
    // since the route group already covers every method in this controller.

    public function dashboard()
    {
        $stats = [
            'users'        => User::count(),
            'hackathons'   => Hackathon::count(),
            'achievements' => Achievement::count(),
        ];
        $recentUsers = User::latest()->limit(5)->get();
        return view('admin.dashboard.index', compact('stats', 'recentUsers'));
    }

    // ── Hackathons ──
    public function hackathons()
    {
        $hackathons = Hackathon::latest()->get();
        return view('admin.hackathons.index', compact('hackathons'));
    }

    public function storeHackathon(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:150',
            'organizer'  => 'required|string|max:150',
            'category'   => 'required|string',
            'type'       => 'required|in:Solo,Team,Both',
            'mode'       => 'required|in:Online,Offline,Hybrid',
            'deadline'   => 'required|date',
        ]);

        Hackathon::create([
            'title'               => $request->title,
            'organizer'           => $request->organizer,
            'description'         => $request->description,
            'category'            => $request->category,
            'type'                => $request->type,
            'mode'                => $request->mode,
            'deadline'            => $request->deadline,
            'prize'               => $request->prize,
            'registration_link'   => $request->registration_link,
            'banner_emoji'        => $request->banner_emoji ?? '🏆',
            'banner_color'        => $request->banner_color ?? 'linear-gradient(135deg,#1e3a5f,#0f2542)',
            'is_intra_university' => $request->boolean('is_intra_university'),
            'is_featured'         => $request->boolean('is_featured'),
        ]);

        return back()->with('success', 'Hackathon added!');
    }

    public function deleteHackathon(Hackathon $hackathon)
    {
        $hackathon->delete();
        return back()->with('success', 'Hackathon deleted.');
    }

    // ── Hall of Fame ──
    public function achievements()
    {
        $achievements = Achievement::with('members.user')->latest()->get();
        $users        = User::orderBy('name')->get();
        return view('admin.achievements.index', compact('achievements', 'users'));
    }

    public function storeAchievement(Request $request)
    {
        $request->validate([
            'title'          => 'required|string|max:150',
            'hackathon_name' => 'required|string|max:150',
            'result'         => 'required|in:Champion,Runner-Up,Top-3,Finalist,Participant',
            'achieved_at'    => 'required|date',
        ]);

        $achievement = Achievement::create([
            'title'          => $request->title,
            'description'    => $request->description,
            'hackathon_name' => $request->hackathon_name,
            'result'         => $request->result,
            'demo_link'      => $request->demo_link,
            'github_link'    => $request->github_link,
            'is_featured'    => $request->boolean('is_featured'),
            'achieved_at'    => $request->achieved_at,
        ]);

        // Add members
        if ($request->members) {
            foreach ($request->members as $userId => $data) {
                if (!empty($data['include'])) {
                    AchievementMember::create([
                        'achievement_id' => $achievement->id,
                        'user_id'        => $userId,
                        'role'           => $data['role'] ?? 'Member',
                        'contribution'   => $data['contribution'] ?? null,
                    ]);
                }
            }
        }

        return back()->with('success', 'Achievement added to Hall of Fame!');
    }
}