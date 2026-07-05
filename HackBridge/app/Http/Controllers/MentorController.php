<?php
// FILE: app/Http/Controllers/MentorController.php

namespace App\Http\Controllers;

use App\Models\Mentor;
use App\Models\MentorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MentorController extends Controller
{
    public function index()
    {
        $mentors       = Mentor::with('user')->where('is_active', true)->get();
        $isMentor      = Mentor::where('user_id', Auth::id())->exists();
        $myRequests    = MentorRequest::where('requester_id', Auth::id())
                                      ->with('mentor.user', 'project')
                                      ->latest()->get();
        $incomingReqs  = null;

        if ($isMentor) {
            $mentor       = Mentor::where('user_id', Auth::id())->first();
            $incomingReqs = MentorRequest::where('mentor_id', $mentor->id)
                                         ->with('requester', 'project')
                                         ->latest()->get();
        }

        return view('mentor.index', compact('mentors', 'isMentor', 'myRequests', 'incomingReqs'));
    }

    public function becomeMentor(Request $request)
    {
        $request->validate([
            'expertise'         => 'required|string|max:300',
            'availability_note' => 'nullable|string|max:200',
        ]);

        Mentor::firstOrCreate(
            ['user_id' => Auth::id()],
            [
                'expertise'         => $request->expertise,
                'availability_note' => $request->availability_note,
                'is_active'         => true,
            ]
        );

        return back()->with('success', 'You are now listed as a mentor!');
    }

    public function requestMentor(Request $request, Mentor $mentor)
    {
        $request->validate([
            'message'    => 'required|string|min:20',
            'project_id' => 'nullable|exists:projects,id',
        ]);

        $already = MentorRequest::where('mentor_id', $mentor->id)
                                ->where('requester_id', Auth::id())
                                ->where('status', 'pending')
                                ->exists();

        if ($already) {
            return back()->with('error', 'You already have a pending request to this mentor.');
        }

        MentorRequest::create([
            'mentor_id'    => $mentor->id,
            'requester_id' => Auth::id(),
            'project_id'   => $request->project_id ?: null,
            'message'      => $request->message,
            'status'       => 'pending',
        ]);

        return back()->with('success', 'Mentor request sent!');
    }

    public function respondRequest(Request $request, MentorRequest $mentorRequest)
    {
        $mentor = Mentor::where('user_id', Auth::id())->first();
        if (!$mentor || $mentorRequest->mentor_id !== $mentor->id) abort(403);

        $mentorRequest->update(['status' => $request->status]);
        return back()->with('success', 'Request ' . $request->status . '.');
    }
}