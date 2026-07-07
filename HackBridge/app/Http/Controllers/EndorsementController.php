<?php
// FILE: app/Http/Controllers/EndorsementController.php

namespace App\Http\Controllers;

use App\Models\Endorsement;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EndorsementController extends Controller
{
    public function index()
    {
        $user         = Auth::user()->load('skills', 'endorsementsReceived.skill', 'endorsementsReceived.endorser');
        $endorsements = $user->endorsementsReceived()->with('skill', 'endorser')->latest()->get();
        return view('endorsements.index', compact('user', 'endorsements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'endorsed_id' => 'required|exists:users,id',
            'skill_id'    => 'required|exists:skills,id',
            'note'        => 'nullable|string|max:200',
        ]);

        if ($request->endorsed_id == Auth::id()) {
            return back()->with('error', 'You cannot endorse yourself.');
        }

        $exists = Endorsement::where('endorser_id', Auth::id())
                             ->where('endorsed_id', $request->endorsed_id)
                             ->where('skill_id', $request->skill_id)
                             ->exists();

        if ($exists) {
            return back()->with('error', 'You already endorsed this skill for this person.');
        }

        Endorsement::create([
            'endorser_id' => Auth::id(),
            'endorsed_id' => $request->endorsed_id,
            'skill_id'    => $request->skill_id,
            'note'        => $request->note,
        ]);

        return back()->with('success', 'Skill endorsed successfully!');
    }

    // Endorse from profile page
    public function endorseUser(Request $request, User $user)
    {
        $request->validate([
            'skill_id' => 'required|exists:skills,id',
            'note'     => 'nullable|string|max:200',
        ]);

        if ($user->id == Auth::id()) {
            return back()->with('error', 'You cannot endorse yourself.');
        }

        $exists = Endorsement::where('endorser_id', Auth::id())
                             ->where('endorsed_id', $user->id)
                             ->where('skill_id', $request->skill_id)
                             ->exists();

        if ($exists) {
            return back()->with('error', 'You already endorsed this skill.');
        }

        Endorsement::create([
            'endorser_id' => Auth::id(),
            'endorsed_id' => $user->id,
            'skill_id'    => $request->skill_id,
            'note'        => $request->note,
        ]);

        return back()->with('success', 'Endorsed!');
    }
}