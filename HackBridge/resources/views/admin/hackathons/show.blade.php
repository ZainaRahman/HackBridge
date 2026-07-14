@extends('layouts.app')
@section('title', $hackathon->title)
@section('page-title', $hackathon->title)

@section('content')

<a href="{{ route('hackathons.index') }}" class="btn btn-ghost btn-sm" style="margin-bottom:16px;">← Back to Hackathons</a>

<div class="card" style="margin-bottom:24px;">
    <div style="display:flex;align-items:flex-start;gap:20px;">
        <div class="hack-banner" style="width:64px;height:64px;font-size:32px;flex-shrink:0;background:var(--surface2);border-radius:14px;display:flex;align-items:center;justify-content:center;">
            {{ $hackathon->banner_emoji }}
        </div>
        <div style="flex:1;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                <h2 style="font-family:'Syne',sans-serif;font-size:22px;font-weight:800;color:var(--white);">{{ $hackathon->title }}</h2>
                @if($hackathon->is_featured)
                <span class="chip chip-orange">⭐ Featured</span>
                @endif
            </div>
            <div style="font-size:13px;color:var(--muted);margin-bottom:12px;">{{ $hackathon->organizer }}</div>
            <div class="hack-chips" style="margin-bottom:14px;">
                <span class="chip chip-blue">{{ $hackathon->mode }}</span>
                <span class="chip chip-cyan">{{ $hackathon->type }}</span>
                <span class="chip chip-violet">{{ $hackathon->category }}</span>
                <span class="chip {{ $hackathon->is_intra_university ? 'chip-green' : 'chip-blue' }}">
                    {{ $hackathon->is_intra_university ? '🏫 Intra-University' : '🌐 National/International' }}
                </span>
            </div>
            @if($hackathon->description)
            <p style="font-size:13.5px;color:var(--muted);line-height:1.7;margin-bottom:16px;">{{ $hackathon->description }}</p>
            @endif

            <div style="display:flex;align-items:center;gap:24px;flex-wrap:wrap;">
                <div>
                    <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:2px;">Deadline</div>
                    <div style="font-size:14px;font-weight:700;color:var(--orange);">
                        {{ \Carbon\Carbon::parse($hackathon->deadline)->format('M d, Y') }}
                        <span style="font-size:11px;color:var(--muted);font-weight:400;">
                            ({{ \Carbon\Carbon::parse($hackathon->deadline)->diffForHumans() }})
                        </span>
                    </div>
                </div>
                @if($hackathon->prize)
                <div>
                    <div style="font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:2px;">Prize</div>
                    <div style="font-size:14px;font-weight:700;color:var(--green);">💰 {{ $hackathon->prize }}</div>
                </div>
                @endif
            </div>

            <div style="margin-top:20px;display:flex;gap:10px;">
                @if($hackathon->registration_link)
                <a href="{{ $hackathon->registration_link }}" target="_blank" class="btn btn-primary btn-sm">Register →</a>
                @endif
                <a href="{{ route('projects.create') }}?hackathon={{ $hackathon->id }}" class="btn btn-outline btn-sm">Start a Team Project</a>
            </div>
        </div>
    </div>
</div>

{{-- Teams already building for this hackathon --}}
<div class="sec-header" style="margin-bottom:14px;">
    <div class="sec-title">📋 Teams Building for This Hackathon</div>
</div>
<div class="grid-3">
@forelse($hackathon->projects as $project)
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
    <div class="empty-state">
        <span class="es-icon">📋</span>
        <p>No teams have started a project for this hackathon yet</p>
        <a href="{{ route('projects.create') }}?hackathon={{ $hackathon->id }}" class="btn btn-primary">Be the First Team</a>
    </div>
</div>
@endforelse
</div>

@endsection