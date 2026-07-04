@extends('layouts.app')
@section('title', $project->title)
@section('page-title', 'Project Details')

@section('content')
<div style="max-width:780px;">

{{-- Header --}}
<div class="card" style="margin-bottom:16px;">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;">
        <div style="flex:1;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                <span class="chip chip-{{ $project->category === 'AI' ? 'violet' : ($project->category === 'Web' ? 'blue' : 'cyan') }}">{{ $project->category }}</span>
                <span class="chip chip-green">{{ ucfirst($project->status) }}</span>
                @if($project->hackathon)
                <span class="chip chip-orange">🏆 {{ $project->hackathon->title }}</span>
                @endif
            </div>
            <h2 style="font-family:'Syne',sans-serif;font-size:22px;font-weight:800;color:var(--white);margin-bottom:10px;">{{ $project->title }}</h2>
            <p style="font-size:14px;color:var(--muted);line-height:1.7;">{{ $project->description }}</p>
        </div>
        <div style="text-align:right;flex-shrink:0;">
            <div style="font-size:11px;color:var(--muted);margin-bottom:4px;">Team size</div>
            <div style="font-family:'Syne',sans-serif;font-size:24px;font-weight:800;color:var(--white);">{{ $project->team_size }}</div>
            <div style="font-size:11px;color:var(--muted);">members needed</div>
        </div>
    </div>

    @if($project->required_skills && count($project->required_skills))
    <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border);">
        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin-bottom:8px;">Required Skills</div>
        <div style="display:flex;flex-wrap:wrap;gap:6px;">
            @foreach($project->required_skills as $skill)
            <span class="chip chip-blue">{{ $skill }}</span>
            @endforeach
        </div>
    </div>
    @endif

    @if($project->prerequisites)
    <div style="margin-top:14px;padding:14px;background:rgba(59,130,246,0.06);border:1px solid rgba(59,130,246,0.15);border-radius:8px;">
        <div style="font-size:11px;font-weight:700;color:var(--blue);margin-bottom:6px;">⚡ PREREQUISITES</div>
        <div style="font-size:13px;color:var(--muted);line-height:1.6;">{{ $project->prerequisites }}</div>
    </div>
    @endif

    <div style="display:flex;align-items:center;gap:16px;margin-top:16px;padding-top:14px;border-top:1px solid var(--border);">
        <div style="display:flex;align-items:center;gap:8px;">
            <div class="sb-avatar" style="width:32px;height:32px;font-size:11px;">{{ $project->owner->initials() }}</div>
            <div>
                <div style="font-size:13px;font-weight:600;color:var(--white);">{{ $project->owner->name }}</div>
                <div style="font-size:11px;color:var(--muted);">{{ $project->owner->department }} · Project Owner</div>
            </div>
        </div>
        @if($project->deadline)
        <div style="margin-left:auto;text-align:right;">
            <div style="font-size:11px;color:var(--muted);">Deadline</div>
            <div style="font-size:13px;font-weight:700;color:var(--orange);">{{ \Carbon\Carbon::parse($project->deadline)->format('M d, Y') }}</div>
        </div>
        @endif
    </div>
</div>

{{-- Apply Section --}}
@if(auth()->id() !== $project->owner_id && $project->status === 'recruiting')
<div class="card" style="margin-bottom:16px;">
    @if($alreadyApplied)
    <div style="text-align:center;padding:20px;">
        <div style="font-size:32px;margin-bottom:8px;">✅</div>
        <div style="font-family:'Syne',sans-serif;font-size:16px;font-weight:700;color:var(--white);margin-bottom:4px;">Application Sent!</div>
        <div style="font-size:13px;color:var(--muted);">The team leader will review your application and get back to you.</div>
    </div>
    @else
    <div class="card-title">Apply to Join This Team</div>
    <form method="POST" action="{{ route('projects.apply', $project->id) }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Your Pitch <span style="color:var(--muted);font-weight:400;">(min 20 characters)</span></label>
            <textarea name="pitch" class="form-control" rows="4" placeholder="Tell the team why you're a great fit. Mention your relevant skills, experience, and what you can contribute..." required minlength="20"></textarea>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Send Application →</button>
    </form>
    @endif
</div>
@endif

{{-- Applications (owner only) --}}
@if(auth()->id() === $project->owner_id && $project->applications->count() > 0)
<div class="card">
    <div class="card-title">Applications ({{ $project->applications->count() }})</div>
    @foreach($project->applications as $app)
    <div style="padding:14px 0;border-bottom:1px solid var(--border);">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                <div class="sb-avatar" style="width:32px;height:32px;font-size:11px;">{{ $app->user->initials() }}</div>
                <div>
                    <div style="font-size:13px;font-weight:600;color:var(--white);">{{ $app->user->name }}</div>
                    <div style="font-size:11px;color:var(--muted);">{{ $app->user->department }} · Year {{ $app->user->year }}</div>
                </div>
            </div>
            <span class="chip chip-{{ $app->status === 'accepted' ? 'green' : ($app->status === 'rejected' ? 'red' : 'orange') }}">
                {{ ucfirst($app->status) }}
            </span>
        </div>
        <p style="font-size:13px;color:var(--muted);line-height:1.6;margin-bottom:12px;">{{ $app->pitch }}</p>
        @if($app->status === 'pending')
        <div style="display:flex;gap:8px;">
            <form method="POST" action="{{ route('applications.respond', $app->id) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="accepted">
                <button type="submit" class="btn btn-green btn-sm">✓ Accept</button>
            </form>
            <form method="POST" action="{{ route('applications.respond', $app->id) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="rejected">
                <button type="submit" class="btn btn-danger btn-sm">✕ Reject</button>
            </form>
        </div>
        @endif
    </div>
    @endforeach
</div>
@endif

</div>
@endsection