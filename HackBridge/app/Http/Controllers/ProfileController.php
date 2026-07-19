<?php
namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $user   = Auth::user()->load('skills');
        $skills = Skill::orderBy('category')->orderBy('name')->get();
        return view('profile.edit', compact('user', 'skills'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:100',
            'department'   => 'nullable|string',
            'year'         => 'nullable|integer|min:1|max:5',
            'bio'          => 'nullable|string|max:500',
            'github'       => 'nullable|url',
            'linkedin'     => 'nullable|url',
            'availability' => 'required|in:open,looking,busy,in_team',
        ]);

        $user = Auth::user();
        $user->update($request->only('name','department','year','bio','github','linkedin','availability'));

        // Update skills
        if ($request->has('skills')) {
            $syncData = [];
            foreach ($request->skills as $skillId => $level) {
                $syncData[$skillId] = ['level' => $level];
            }
            $user->skills()->sync($syncData);
        } else {
            $user->skills()->detach();
        }

        return back()->with('success', 'Profile updated successfully!');
    }

    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', ['password' => ['required', 'current_password']]);
        $user = Auth::user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function show($id)
    {
        // 'projects' = teams this user owns; 'joinedProjects' = teams they were
        // accepted onto as a member (see User::joinedProjects()). Both are shown
        // on the profile now, not just owned ones.
        // 'endorsementsReceived.skill' is eager-loaded so the skills list can show
        // an endorsement count per skill without triggering N+1 queries.
        $user = \App\Models\User::with([
            'skills',
            'projects',
            'joinedProjects',
            'endorsementsReceived.skill',
        ])->findOrFail($id);

        return view('profile.show', compact('user'));
    }
}