@extends('layouts.app')
@section('title','Mentors')
@section('page-title','Mentor Hub')

@section('content')
<div class="page-header">
    <h2>Mentor Hub</h2>
    <p>Connect with mentors or offer your own expertise to hackathon teams</p>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-error">{{ session('error') }}</div>
@endif

{{-- Become a Mentor --}}
@if(!$isMentor)
<div class="sec-header" style="margin-bottom:14px;">
    <div class="sec-title">🎓 Become a Mentor</div>
</div>
<div class="card" style="margin-bottom:36px;padding:20px;">
    <form method="POST" action="{{ route('mentors.become') }}">
        @csrf
        <div class="form-group" style="margin-bottom:12px;">
            <label>Your Expertise</label>
            <textarea name="expertise" maxlength="300" required class="form-control"
                placeholder="e.g. Full-stack web development, ML, UI/UX design...">{{ old('expertise') }}</textarea>
            @error('expertise')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group" style="margin-bottom:12px;">
            <label>Availability Note (optional)</label>
            <input type="text" name="availability_note" maxlength="200" class="form-control"
                placeholder="e.g. Weeknights after 8pm, weekends" value="{{ old('availability_note') }}">
            @error('availability_note')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-primary btn-sm">List Me as a Mentor</button>
    </form>
</div>
@endif

{{-- Incoming Requests (for mentors) --}}
@if($isMentor)
<div class="sec-header" style="margin-bottom:14px;">
    <div class="sec-title">📥 Incoming Mentor Requests</div>
</div>
<div class="grid-3" style="margin-bottom:36px;">
@forelse($incomingReqs as $r)
<div class="hack-card">
    <div class="hack-body">
        <div class="hack-name">{{ $r->requester->name }}</div>
        @if($r->project)
        <div class="hack-org">Project: {{ $r->project->title }}</div>
        @endif
        <div class="hack-chips">
            <span class="chip {{ $r->status === 'accepted' ? 'chip-green' : ($r->status === 'rejected' ? 'chip-red' : 'chip-blue') }}">
                {{ ucfirst($r->status) }}
            </span>
        </div>
        <p style="font-size:12px;color:var(--muted);margin-bottom:10px;">{{ $r->message }}</p>
        @if($r->status === 'pending')
        <div class="hack-footer">
            <form method="POST" action="{{ route('mentors.respond', $r) }}">
                @csrf
                <input type="hidden" name="status" value="accepted">
                <button type="submit" class="btn btn-primary btn-sm">Accept</button>
            </form>
            <form method="POST" action="{{ route('mentors.respond', $r) }}">
                @csrf
                <input type="hidden" name="status" value="rejected">
                <button type="submit" class="btn btn-outline btn-sm">Decline</button>
            </form>
        </div>
        @endif
    </div>
</div>
@empty
<div style="grid-column:span 3;">
    <div class="empty-state"><span class="es-icon">📥</span><p>No mentor requests yet</p></div>
</div>
@endforelse
</div>
@endif

{{-- My Sent Requests --}}
<div class="sec-header" style="margin-bottom:14px;">
    <div class="sec-title">📤 My Requests</div>
</div>
<div class="grid-3" style="margin-bottom:36px;">
@forelse($myRequests as $r)
<div class="hack-card">
    <div class="hack-body">
        <div class="hack-name">{{ $r->mentor->user->name }}</div>
        @if($r->project)
        <div class="hack-org">Project: {{ $r->project->title }}</div>
        @endif
        <div class="hack-chips">
            <span class="chip {{ $r->status === 'accepted' ? 'chip-green' : ($r->status === 'rejected' ? 'chip-red' : 'chip-blue') }}">
                {{ ucfirst($r->status) }}
            </span>
        </div>
        <p style="font-size:12px;color:var(--muted);">{{ $r->message }}</p>
    </div>
</div>
@empty
<div style="grid-column:span 3;">
    <div class="empty-state"><span class="es-icon">📤</span><p>You haven't requested a mentor yet</p></div>
</div>
@endforelse
</div>

{{-- Available Mentors --}}
<div class="sec-header" style="margin-bottom:14px;">
    <div class="sec-title">🧑‍🏫 Available Mentors</div>
</div>
<div class="grid-3">
@forelse($mentors as $mentor)
<div class="hack-card">
    <div class="hack-banner" style="background:var(--violet);">🧑‍🏫</div>
    <div class="hack-body">
        <div class="hack-name">{{ $mentor->user->name }}</div>
        <div class="hack-org">{{ $mentor->expertise }}</div>
        @if($mentor->availability_note)
        <div class="hack-chips">
            <span class="chip chip-cyan">{{ $mentor->availability_note }}</span>
        </div>
        @endif
        <div class="hack-footer">
            <button type="button" class="btn btn-outline btn-sm" onclick="toggleForm({{ $mentor->id }})">Request Mentorship →</button>
        </div>
        <form id="request-form-{{ $mentor->id }}" method="POST" action="{{ route('mentors.request', $mentor) }}" style="display:none;margin-top:12px;">
            @csrf
            <div class="form-group" style="margin-bottom:8px;">
                <textarea name="message" minlength="20" required class="form-control"
                    placeholder="Tell them what you need help with (min 20 chars)"></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Send Request</button>
        </form>
    </div>
</div>
@empty
<div style="grid-column:span 3;">
    <div class="empty-state"><span class="es-icon">🧑‍🏫</span><p>No mentors listed yet</p></div>
</div>
@endforelse
</div>

<script>
function toggleForm(id) {
    const el = document.getElementById('request-form-' + id);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
</script>

@endsection