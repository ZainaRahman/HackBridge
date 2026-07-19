<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    // Redistributed weights now that semantic scoring is removed
    private const SKILL_WEIGHT = 0.6;
    private const DEPT_WEIGHT = 0.2;
    private const AVAILABILITY_WEIGHT = 0.2;

   public function projectMatches(Project $project): \Illuminate\Http\JsonResponse|\Illuminate\View\View
{
    $candidates = User::with('skills')
        ->where('id', '!=', $project->owner_id)
        ->get()
        ->map(fn (User $user) => $this->score($user, $project))
        ->sortByDesc('match_score')
        ->values();

    if (request()->wantsJson()) {
        return response()->json(['data' => $candidates]);
    }

    return view('projects.matches', compact('project', 'candidates'));
}

    public function myMatch(Request $request, Project $project): JsonResponse
    {
        return response()->json($this->score($request->user(), $project));
    }

    private function score(User $user, Project $project): array
{
    $required = collect($project->required_skills ?? []); // array of skill NAMES
    $userSkillNames = $user->skills->pluck('name');

    $overlap = $required->isEmpty()
        ? 0
        : $required->intersect($userSkillNames)->count() / $required->count();

    $deptMatch = $project->dept_preference === 'Any'
        || $project->dept_preference === $user->department
        ? 1 : 0;

    $availabilityScore = match ($user->availability) {
        'open'    => 1,
        'looking' => 0.7,
        'busy'    => 0.2,
        'in_team' => 0,
        default   => 0.5,
    };

    $score = ($overlap * self::SKILL_WEIGHT)
        + ($deptMatch * self::DEPT_WEIGHT)
        + ($availabilityScore * self::AVAILABILITY_WEIGHT);

    return [
        'user_id'       => $user->id,
        'name'          => $user->name,
        'department'    => $user->department,
        'availability'  => $user->availability,
        'skill_overlap' => round($overlap * 100),
        'match_score'   => round($score * 100),
    ];
}
}