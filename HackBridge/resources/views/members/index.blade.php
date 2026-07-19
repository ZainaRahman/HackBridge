@extends('layouts.app')
@section('title','Browse Members')
@section('page-title','Browse Members')

@section('content')
<div class="page-header">
    <h2>Find Teammates</h2>
    <p>Browse students open to teaming up for projects and hackathons</p>
</div>

<div class="grid-3" style="grid-template-columns:repeat(auto-fill,minmax(220px,1fr));">
@forelse($members as $member)
<div class="member-card">
    <div class="member-avatar">{{ $member->initials() }}</div>
    <div class="member-name">{{ $member->name }}</div>
    <div class="member-dept">{{ $member->department ?? '—' }} · Year {{ $member->year ?? '—' }}</div>

    <span style="display:inline-block;font-size:10px;font-weight:700;padding:3px 10px;border-radius:20px;margin-bottom:10px;background:{{ $member->availabilityColor() }}20;color:{{ $member->availabilityColor() }};">
        ● {{ $member->availabilityLabel() }}
    </span>

    @if($member->skills->count())
    <div class="member-skills">
        @foreach($member->skills->take(3) as $skill)
        <span class="chip chip-blue">{{ $skill->name }}</span>
        @endforeach
        @if($member->skills->count() > 3)
        <span class="chip chip-blue">+{{ $member->skills->count() - 3 }}</span>
        @endif
    </div>
    @endif

    <a href="{{ route('profile.show', $member->id) }}" class="btn btn-outline btn-sm" style="width:100%;justify-content:center;">
        View Profile
    </a>
</div>
@empty
<div style="grid-column:span 4;">
    <div class="empty-state"><span class="es-icon">👥</span><p>No other members yet</p></div>
</div>
@endforelse
</div>

<div style="margin-top:24px;">{{ $members->links() }}</div>
@endsection