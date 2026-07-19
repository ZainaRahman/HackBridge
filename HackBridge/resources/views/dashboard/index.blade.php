@extends('layouts.app')
@section('title','Dashboard')
@section('page-title','Dashboard')

@section('content')

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon si-blue">📋</div>
        <div><div class="stat-val">{{ $totalProjects }}</div><div class="stat-lbl">Open Projects</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-violet">👥</div>
        <div><div class="stat-val">{{ $totalMembers }}</div><div class="stat-lbl">Members</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-green">✓</div>
        <div><div class="stat-val">{{ $myApplications }}</div><div class="stat-lbl">My Applications</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-orange">🏆</div>
        <div><div class="stat-val">{{ $hackathons->count() }}</div><div class="stat-lbl">Upcoming Hackathons</div></div>
    </div>
</div>

{{-- Main Grid --}}
<div class="grid-2" style="margin-bottom:20px;">

    {{-- Upcoming Hackathons --}}
    <div class="card">
        <div class="sec-header">
            <div class="sec-title">🏆 Upcoming Hackathons</div>
            <a href="{{ route('hackathons.index') }}" class="btn btn-ghost btn-sm">View All</a>
        </div>
        @forelse($hackathons as $h)
        <div style="display:flex;align-items:center;gap:14px;padding:12px 0;border-bottom:1px solid var(--border);">
            <div style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;background:var(--surface2);flex-shrink:0;">{{ $h->banner_emoji }}</div>
            <div style="flex:1;">
                <div style="font-size:13.5px;font-weight:700;color:var(--white);margin-bottom:2px;">{{ $h->title }}</div>
                <div style="font-size:11px;color:var(--muted);">{{ $h->organizer }}</div>
            </div>
            <div style="text-align:right;">
                <div style="font-size:11px;font-weight:700;color:var(--orange);">{{ \Carbon\Carbon::parse($h->deadline)->format('M d') }}</div>
                <div style="font-size:10px;color:var(--muted);">{{ \Carbon\Carbon::parse($h->deadline)->diffForHumans() }}</div>
            </div>
        </div>
        @empty
        <div class="empty-state" style="padding:20px;"><span class="es-icon">📅</span><p>No upcoming hackathons</p></div>
        @endforelse
    </div>

    {{-- Suggested Members --}}
    <div class="card">
        <div class="sec-header">
            <div class="sec-title">👥 Suggested Teammates</div>
            <a href="{{ route('members.index') }}" class="btn btn-ghost btn-sm">Browse All</a>
        </div>
        @forelse($suggested as $member)
        <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--border);">
            <div class="sb-avatar" style="width:36px;height:36px;font-size:12px;flex-shrink:0;">{{ $member->initials() }}</div>
            <div style="flex:1;">
                <div style="font-size:13px;font-weight:600;color:var(--white);">{{ $member->name }}</div>
                <div style="font-size:11px;color:var(--muted);">{{ $member->department }} · Year {{ $member->year }}</div>
            </div>
            <span style="font-size:10px;font-weight:600;padding:3px 8px;border-radius:20px;background:{{ $member->availabilityColor() }}20;color:{{ $member->availabilityColor() }};">
                ● Open
            </span>
        </div>
        @empty
        <div class="empty-state" style="padding:20px;"><span class="es-icon">👤</span><p>No members yet</p></div>
        @endforelse
    </div>

</div>

{{-- My Active Workspaces --}}
<div class="card" style="margin-bottom:20px;">
    <div class="sec-header">
        <div class="sec-title">🗂 My Active Workspaces</div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:12px;">
        @forelse($myWorkspaces as $ws)
        <a href="{{ route('workspace.show', $ws->id) }}" style="display:block;background:var(--bg2);border-radius:10px;padding:14px;text-decoration:none;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                <span class="chip {{ $ws->owner_id === auth()->id() ? 'chip-violet' : 'chip-blue' }}">
                    {{ $ws->owner_id === auth()->id() ? 'Owner' : 'Member' }}
                </span>
                @if($ws->owner_id === auth()->id() && $ws->ghost_flags_count > 0)
                <span title="Inactive member flagged" style="font-size:13px;">👻</span>
                @endif
            </div>
            <div style="font-size:13.5px;font-weight:700;color:var(--white);margin-bottom:6px;">{{ $ws->title }}</div>
            <div style="font-size:11px;color:var(--muted);">
                {{ $ws->pending_tasks_count }} {{ Str::plural('task', $ws->pending_tasks_count) }} pending
            </div>
        </a>
        @empty
        <div style="grid-column:1/-1;">
            <div class="empty-state" style="padding:20px;"><span class="es-icon">🗂</span><p>You're not on any active teams yet</p></div>
        </div>
        @endforelse
    </div>
</div>

{{-- Recent Projects --}}
<div class="sec-header">
    <div class="sec-title">📋 Open Projects</div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">+ Post Project</a>
        <a href="{{ route('projects.index') }}"  class="btn btn-ghost btn-sm">View All</a>
    </div>
</div>

<div class="grid-3">
@forelse($projects as $project)
<div class="project-card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
        <span class="chip chip-{{ $project->category === 'AI' ? 'violet' : ($project->category === 'Web' ? 'blue' : 'cyan') }}">
            {{ $project->category }}
        </span>
        <span style="font-size:11px;color:var(--muted);">{{ $project->team_size }} members</span>
    </div>
    <div class="project-card-title">{{ $project->title }}</div>
    <div class="project-card-desc">{{ $project->description }}</div>
    <div class="project-footer">
        <div style="display:flex;align-items:center;gap:8px;">
            <div class="sb-avatar" style="width:24px;height:24px;font-size:9px;">{{ $project->owner->initials() }}</div>
            <span style="font-size:11px;color:var(--muted);">{{ $project->owner->name }}</span>
        </div>
        <a href="{{ route('projects.show', $project->id) }}" class="btn btn-outline btn-sm">View →</a>
    </div>
</div>
@empty
<div style="grid-column:span 3;">
    <div class="empty-state"><span class="es-icon">📋</span><p>No open projects yet</p><a href="{{ route('projects.create') }}" class="btn btn-primary">Post First Project</a></div>
</div>
@endforelse
</div>

@endsection