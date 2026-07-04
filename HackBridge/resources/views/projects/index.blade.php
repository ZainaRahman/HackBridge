@extends('layouts.app')
@section('title','Find Projects')
@section('page-title','Find Projects')

@section('content')
<div class="page-header">
    <h2>Open Projects</h2>
    <p>Find a project that matches your skills and apply to join the team</p>
</div>

<form method="GET" action="{{ route('projects.index') }}">
<div class="filter-row">
    <input name="search" class="filter-input" placeholder="🔍 Search projects..." value="{{ request('search') }}" style="flex:1;min-width:200px;">
    <select name="category" class="filter-input">
        <option value="">All Categories</option>
        @foreach(['AI','Web','Mobile','Hardware','Research','Design','Other'] as $cat)
        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
        @endforeach
    </select>
    <select name="dept" class="filter-input">
        <option value="">All Departments</option>
        @foreach(['Any','CSE','EEE','ME','CE','ECE'] as $d)
        <option value="{{ $d }}" {{ request('dept') === $d ? 'selected' : '' }}>{{ $d }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn btn-primary">Filter</button>
    <a href="{{ route('projects.create') }}" class="btn btn-outline">+ Post Project</a>
</div>
</form>

@if($projects->isEmpty())
<div class="empty-state">
    <span class="es-icon">📋</span>
    <p>No projects found</p>
    <a href="{{ route('projects.create') }}" class="btn btn-primary">Post the First Project</a>
</div>
@else
<div class="grid-3">
@foreach($projects as $project)
<div class="project-card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
        <span class="chip chip-{{ $project->category === 'AI' ? 'violet' : ($project->category === 'Web' ? 'blue' : ($project->category === 'Hardware' ? 'orange' : 'cyan')) }}">
            {{ $project->category }}
        </span>
        <span style="font-size:11px;color:var(--muted);">👥 {{ $project->team_size }} needed</span>
    </div>
    <div class="project-card-title">{{ $project->title }}</div>
    <div class="project-card-desc">{{ $project->description }}</div>

    @if($project->required_skills && count($project->required_skills))
    <div class="project-chips">
        @foreach(array_slice($project->required_skills, 0, 3) as $skill)
        <span class="chip chip-blue">{{ $skill }}</span>
        @endforeach
        @if(count($project->required_skills) > 3)
        <span class="chip chip-blue">+{{ count($project->required_skills) - 3 }}</span>
        @endif
    </div>
    @endif

    <div class="project-footer">
        <div style="display:flex;align-items:center;gap:8px;">
            <div class="sb-avatar" style="width:24px;height:24px;font-size:9px;">{{ $project->owner->initials() }}</div>
            <span style="font-size:11px;color:var(--muted);">{{ $project->owner->department }}</span>
        </div>
        <a href="{{ route('projects.show', $project->id) }}" class="btn btn-outline btn-sm">View →</a>
    </div>
</div>
@endforeach
</div>
<div style="margin-top:24px;">{{ $projects->links() }}</div>
@endif
@endsection