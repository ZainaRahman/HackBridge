@extends('layouts.app')
@section('title','Hackathons')
@section('page-title','Hackathon Board')

@section('content')
<div class="page-header">
    <h2>Hackathon Board</h2>
    <p>Find competitions and build your team directly from each listing</p>
</div>

<div class="sec-header" style="margin-bottom:14px;">
    <div class="sec-title">🏫 Intra-University (KUET)</div>
</div>
<div class="grid-3" style="margin-bottom:36px;">
@forelse($intra as $h)
<div class="hack-card">
    <div class="hack-banner" style="background:{{ $h->banner_color }};">{{ $h->banner_emoji }}</div>
    <div class="hack-body">
        <div class="hack-name">{{ $h->title }}</div>
        <div class="hack-org">{{ $h->organizer }}</div>
        <div class="hack-chips">
            <span class="chip chip-blue">{{ $h->mode }}</span>
            <span class="chip chip-cyan">{{ $h->type }}</span>
            <span class="chip chip-violet">{{ $h->category }}</span>
        </div>
        @if($h->prize)
        <div style="font-size:11px;color:var(--green);font-weight:700;margin-bottom:8px;">💰 {{ $h->prize }}</div>
        @endif
        <div class="hack-footer">
            <div class="hack-deadline">Deadline: <strong>{{ \Carbon\Carbon::parse($h->deadline)->format('M d, Y') }}</strong></div>
            <a href="{{ route('members.index') }}?hackathon={{ $h->id }}" class="btn btn-outline btn-sm">Find Team →</a>
        </div>
    </div>
</div>
@empty
<div style="grid-column:span 3;">
    <div class="empty-state"><span class="es-icon">🏫</span><p>No intra-university hackathons listed yet</p></div>
</div>
@endforelse
</div>

<div class="sec-header" style="margin-bottom:14px;">
    <div class="sec-title">🌐 National & International</div>
</div>
<div class="grid-3">
@forelse($inter as $h)
<div class="hack-card">
    <div class="hack-banner" style="background:{{ $h->banner_color }};">{{ $h->banner_emoji }}</div>
    <div class="hack-body">
        <div class="hack-name">{{ $h->title }}</div>
        <div class="hack-org">{{ $h->organizer }}</div>
        <div class="hack-chips">
            <span class="chip chip-blue">{{ $h->mode }}</span>
            <span class="chip chip-cyan">{{ $h->type }}</span>
            <span class="chip chip-green">{{ $h->category }}</span>
        </div>
        @if($h->prize)
        <div style="font-size:11px;color:var(--green);font-weight:700;margin-bottom:8px;">💰 {{ $h->prize }}</div>
        @endif
        <div class="hack-footer">
            <div class="hack-deadline">Deadline: <strong>{{ \Carbon\Carbon::parse($h->deadline)->format('M d, Y') }}</strong></div>
            <a href="{{ route('projects.create') }}?hackathon={{ $h->id }}" class="btn btn-outline btn-sm">Find Team →</a>
        </div>
    </div>
</div>
@empty
<div style="grid-column:span 3;">
    <div class="empty-state"><span class="es-icon">🌐</span><p>No international hackathons listed yet</p></div>
</div>
@endforelse
</div>
@endsection