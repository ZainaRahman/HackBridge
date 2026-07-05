<?php
// FILE: app/Http/Controllers/HallOfFameController.php

namespace App\Http\Controllers;

use App\Models\Achievement;

class HallOfFameController extends Controller
{
    public function index()
    {
        $featured    = Achievement::with('members.user', 'project')
                                  ->where('is_featured', true)
                                  ->latest('achieved_at')
                                  ->get();

        $all         = Achievement::with('members.user', 'project')
                                  ->latest('achieved_at')
                                  ->paginate(9);

        $champions   = Achievement::where('result', 'Champion')->count();
        $runnersUp   = Achievement::where('result', 'Runner-Up')->count();
        $total       = Achievement::count();

        return view('halloffame.index', compact('featured', 'all', 'champions', 'runnersUp', 'total'));
    }
}