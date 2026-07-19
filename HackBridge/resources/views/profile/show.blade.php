@extends('layouts.app')
@section('title', $user->name)
@section('page-title', 'Member Profile')

@section('content')
<div style="max-width:680px;">

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-error">{{ session('error') }}</div>
@endif

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
                @if(auth()->id() !== $user->id)
                <button type="button" class="btn btn-primary btn-sm" onclick="toggleEndorseForm()">⭐ Endorse a Skill</button>
                @endif
            </div>
        </div>
    </div>

    @if($user->bio)
    <div style="padding-top:16px;border-top:1px solid var(--border);font-size:13.5px;color:var(--muted);line-height:1.7;">
        {{ $user->bio }}
    </div>
    @endif

    {{-- Endorsement form — only visible to other users, only endorses skills this person has actually listed --}}
    @if(auth()->id() !== $user->id && $user->skills->count())
    <div id="endorseForm" style="display:none;margin-top:16px;padding-top:16px;border-top:1px solid var(--border);">
        <form method="POST" action="{{ route('endorsements.user', $user->id) }}">
            @csrf
            <div class="form-group" style="margin-bottom:10px;">
                <label class="form-label">Which skill?</label>
                <select name="skill_id" class="form-control" required>
                    @foreach($user->skills as $skill)
                    <option value="{{ $skill->id }}">{{ $skill->name }} ({{ $skill->pivot->level }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin-bottom:10px;">
                <label class="form-label">Note (optional)</label>
                <input type="text" name="note" maxlength="200" class="form-control" placeholder="e.g. Carried our team through the backend in FictiPay Datathon">
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Submit Endorsement</button>
        </form>
    </div>
    @elseif(auth()->id() !== $user->id)
    <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border);font-size:12px;color:var(--muted);">
        {{ $user->name }} hasn't listed any skills yet, so there's nothing to endorse.
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
            @php
                $endorseCount = $user->endorsementsReceived->where('skill_id', $skill->id)->count();
            @endphp
            <span class="chip chip-blue" title="{{ $endorseCount }} {{ Str::plural('endorsement', $endorseCount) }}">
                {{ $skill->name }} · <span style="opacity:.7;">{{ $skill->pivot->level }}</span>
                @if($endorseCount > 0)
                <span style="opacity:.85;">⭐ {{ $endorseCount }}</span>
                @endif
            </span>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
@endif

@php
    // Owned projects + accepted team memberships, deduplicated and tagged with role.
    $allProjects = $user->projects->concat($user->joinedProjects)->unique('id');
@endphp
@if($allProjects->count())
<div class="card">
    <div class="card-title">Projects</div>
    @foreach($allProjects as $project)
    <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border);">
        <div>
            <div style="display:flex;align-items:center;gap:8px;">
                <div style="font-size:13.5px;font-weight:600;color:var(--white);">{{ $project->title }}</div>
                <span class="chip {{ $project->owner_id === $user->id ? 'chip-violet' : 'chip-blue' }}" style="font-size:9px;">
                    {{ $project->owner_id === $user->id ? 'Owner' : 'Member' }}
                </span>
            </div>
            <div style="font-size:11px;color:var(--muted);">{{ $project->category }}</div>
        </div>
        <a href="{{ route('projects.show', $project->id) }}" class="btn btn-outline btn-sm">View →</a>
    </div>
    @endforeach
</div>
@endif

</div>

<script>
function toggleEndorseForm() {
    const el = document.getElementById('endorseForm');
    if (el) el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
</script>
@endsection