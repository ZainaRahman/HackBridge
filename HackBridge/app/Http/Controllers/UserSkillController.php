<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserSkillController extends Controller
{
    public function edit(Request $request): View
    {
        $skills = Skill::orderBy('category')->orderBy('name')->get()->groupBy('category');
        $userSkillIds = $request->user()->skills()->pluck('skills.id')->toArray();
        $userLevels = $request->user()->skills()->pluck('user_skills.level', 'skills.id');

        return view('profile.skills', compact('skills', 'userSkillIds', 'userLevels'));
    }

    public function sync(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'skills'   => ['array'],
            'skills.*' => ['exists:skills,id'],
            'levels'   => ['array'],
            'levels.*' => ['in:Beginner,Intermediate,Expert'],
        ]);

        $syncData = [];
        foreach ($validated['skills'] ?? [] as $skillId) {
            $syncData[$skillId] = ['level' => $validated['levels'][$skillId] ?? 'Beginner'];
        }

        $request->user()->skills()->sync($syncData);

        return back()->with('status', 'skills-updated');
    }

    public function destroy(Request $request, Skill $skill): RedirectResponse
    {
        $request->user()->skills()->detach($skill->id);

        return back()->with('status', 'skill-removed');
    }
}