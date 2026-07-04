@extends('layouts.app')
@section('title','My Projects')
@section('page-title','My Projects')

@section('content')
<div class="sec-header" style="margin-bottom:20px;">
    <div><h2 style="font-family:'Syne',sans-serif;font-size:20px;font-weight:800;color:var(--white);">My Posted Projects</h2></div>
    <a href="{{ route('projects.create') }}" class="btn btn-primary">+ Post New Project</a>
</div>

@forelse($projects as $project)
<div class="card" style="margin-bottom:14px;">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;">
        <div style="flex:1;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                <span class="chip chip-{{ $project->category === 'AI' ? 'violet' : 'blue' }}">{{ $project->category }}</span>
                <span class="chip chip-{{ $project->status === 'recruiting' ? 'green' : ($project->status === 'in_progress' ? 'orange' : 'violet') }}">
                    {{ ucfirst(str_replace('_',' ',$project->status)) }}
                </span>
                @if($project->hackathon)
                <span class="chip chip-orange">🏆 {{ $project->hackathon->title }}</span>
                @endif
            </div>
            <div style="font-family:'Syne',sans-serif;font-size:16px;font-weight:700;color:var(--white);margin-bottom:6px;">{{ $project->title }}</div>
            <div style="font-size:13px;color:var(--muted);line-height:1.6;">{{ Str::limit($project->description, 120) }}</div>
        </div>
        <div style="text-align:right;flex-shrink:0;">
            <div style="font-size:22px;font-weight:800;color:var(--white);font-family:'Syne',sans-serif;">{{ $project->applications->count() }}</div>
            <div style="font-size:11px;color:var(--muted);">applications</div>
            <div style="margin-top:4px;">
                <span style="font-size:11px;color:var(--green);font-weight:700;">{{ $project->applications->where('status','accepted')->count() }} accepted</span>
            </div>
        </div>
    </div>
    <div style="display:flex;gap:8px;margin-top:14px;padding-top:12px;border-top:1px solid var(--border);">
        <a href="{{ route('projects.show', $project->id) }}" class="btn btn-outline btn-sm">View & Manage Applications</a>
    </div>
</div>
@empty
<div class="empty-state card">
    <span class="es-icon">📋</span>
    <p>You haven't posted any projects yet</p>
    <a href="{{ route('projects.create') }}" class="btn btn-primary">Post Your First Project</a>
</div>
@endforelse
@endsection