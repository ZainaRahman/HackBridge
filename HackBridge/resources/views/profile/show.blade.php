@extends('layouts.app')
@section('title', $user->name)
@section('page-title', 'Member Profile')

@section('content')
<div style="max-width:680px;">
<div class="card" style="margin-bottom:16px;">
    <div class="profile-header">
        <div class="profile-avatar-lg">{{ $user->initials() }}</div>
        <div class="profile-meta">
            <div class="profile-name">{{ $user->name }}</div>
            <div class="profile-dept">{{ $user->department }} · {{ $user->university }} · Year {{ $user->year }}</div>
            <span style="font-size:11px;font-weight:700;padding:4px 12px;border-radius:20px;background:{{ $user->availabilityColor() }}20;color:{{ $user->availabilityColor() }};">
                ● {{ $user->availabilityLabel() }}
            </span>
            <div style="display:flex;gap:10px;margin-top:10px;">
                @if($user->github)
                <a href="{{ $user->github }}" target="_blank" class="btn btn-ghost btn-sm">GitHub →</a>
                @endif
                @if($user->linkedin)
                <a href="{{ $user->linkedin }}" target="_blank" class="btn btn-ghost btn-sm">LinkedIn →</a>
                @endif
            </div>
        </div>
    </div>

    @if($user->bio)
    <div style="padding-top:16px;border-top:1px solid var(--border);font-size:13.5px;color:var(--muted);line-height:1.7;">
        {{ $user->bio }}
    </div>
    @endif
</div>

@if($user->skills->count())
<div class="card" style="margin-bottom:16px;">
    <div class="card-title">Skills</div>
    @foreach($user->skills->groupBy('category') as $category => $skills)
    <div style="margin-bottom:14px;">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin-bottom:8px;">{{ $category }}</div>
        <div style="display:flex;flex-wrap:wrap;gap:6px;">
            @foreach($skills as $skill)
            <span class="chip chip-blue">{{ $skill->name }} · <span style="opacity:.7;">{{ $skill->pivot->level }}</span></span>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
@endif

@if($user->projects->count())
<div class="card">
    <div class="card-title">Projects</div>
    @foreach($user->projects as $project)
    <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);">
        <div>
            <div style="font-size:13.5px;font-weight:600;color:var(--white);">{{ $project->title }}</div>
            <div style="font-size:11px;color:var(--muted);">{{ $project->category }}</div>
        </div>
        <a href="{{ route('projects.show', $project->id) }}" class="btn btn-outline btn-sm">View →</a>
    </div>
    @endforeach
</div>
@endif

</div>
@endsection