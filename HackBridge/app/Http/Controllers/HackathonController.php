<?php
// FILE: app/Http/Controllers/HackathonController.php

namespace App\Http\Controllers;

use App\Models\Hackathon;

class HackathonController extends Controller
{
    public function index()
    {
        $intra = Hackathon::where('is_intra_university', true)->orderBy('deadline')->get();
        $inter = Hackathon::where('is_intra_university', false)->orderBy('deadline')->get();
        return view('hackathons.index', compact('intra', 'inter'));
    }

    public function show(Hackathon $hackathon)
    {
        $hackathon->load('projects.owner');
        return view('hackathons.show', compact('hackathon'));
    }
}