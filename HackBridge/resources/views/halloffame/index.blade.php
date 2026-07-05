@extends('layouts.app')
@section('title','Hall of Fame')
@section('page-title','Hall of Fame')

@section('content')
<div class="page-header">
    <h2>🏆 Hall of Fame</h2>
    <p>Celebrating KUET's top teams and their achievements</p>
</div>

{{-- Stats --}}
<div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:28px;">
    <div class="stat-card">
        <div class="stat-icon si-orange">🏆</div>
        <div><div class="stat-val">{{ $champions }}</div><div class="stat-lbl">Champions</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-violet">🥈</div>
        <div><div class="stat-val">{{ $runnersUp }}</div><div class="stat-lbl">Runner-Ups</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-blue">🎖️</div>
        <div><div class="stat-val">{{ $total }}</div><div class="stat-lbl">Total Achievements</div></div>
    </div>
</div>

{{-- Featured --}}
@if($featured->count())
<div class="sec-header" style="margin-bottom:14px;">
    <div class="sec-title">⭐ Featured Achievements</div>
</div>
<div class="grid-3" style="margin-bottom:32px;">
@foreach($featured as $ach)
<div class="card" style="border-color:rgba(245,158,11,0.3);position:relative;overflow:hidden;">
    <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--orange),var(--violet));"></div>
    <div style="padding-top:8px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
            <span class="chip chip-{{ $ach->result === 'Champion' ? 'orange' : ($ach->result === 'Runner-Up' ? 'violet' : 'blue') }}">
                {{ $ach->result === 'Champion' ? '🏆' : ($ach->result === 'Runner-Up' ? '🥈' : '🎖️') }} {{ $ach->result }}
            </span>
            <span style="font-size:11px;color:var(--muted);">{{ $ach->achieved_at?->format('M Y') }}</span>
        </div>
        <div style="font-family:'Syne',sans-serif;font-size:16px;font-weight:700;color:var(--white);margin-bottom:4px;">{{ $ach->title }}</div>
        <div style="font-size:12px;color:var(--blue);font-weight:600;margin-bottom:10px;">{{ $ach->hackathon_name }}</div>
        @if($ach->description)
        <div style="font-size:12.5px;color:var(--muted);line-height:1.6;margin-bottom:12px;">{{ Str::limit($ach->description, 100) }}</div>
        @endif

        {{-- Team Members --}}
        @if($ach->members->count())
        <div style="margin-bottom:12px;">
            <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin-bottom:8px;">Team</div>
            <div style="display:flex;flex-wrap:wrap;gap:6px;">
                @foreach($ach->members as $m)
                <div style="display:flex;align-items:center;gap:5px;background:var(--bg2);border-radius:20px;padding:3px 10px 3px 4px;">
                    <div class="sb-avatar" style="width:20px;height:20px;font-size:8px;flex-shrink:0;">{{ $m->user->initials() }}</div>
                    <span style="font-size:11px;color:var(--white);font-weight:500;">{{ $m->user->name }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div style="display:flex;gap:8px;">
            @if($ach->github_link)
            <a href="{{ $ach->github_link }}" target="_blank" class="btn btn-ghost btn-sm">GitHub →</a>
            @endif
            @if($ach->demo_link)
            <a href="{{ $ach->demo_link }}" target="_blank" class="btn btn-outline btn-sm">Demo →</a>
            @endif
        </div>
    </div>
</div>
@endforeach
</div>
@endif

{{-- All Achievements --}}
<div class="sec-header" style="margin-bottom:14px;">
    <div class="sec-title">All Achievements</div>
</div>
@if($all->isEmpty())
<div class="empty-state card">
    <span class="es-icon">🏆</span>
    <p>No achievements recorded yet. Win a hackathon to appear here!</p>
</div>
@else
<div class="card" style="padding:0;overflow:hidden;">
    <table class="table">
        <thead>
            <tr>
                <th>Achievement</th>
                <th>Hackathon</th>
                <th>Result</th>
                <th>Team</th>
                <th>Date</th>
                <th>Links</th>
            </tr>
        </thead>
        <tbody>
        @foreach($all as $ach)
        <tr>
            <td style="font-weight:600;color:var(--white);">{{ $ach->title }}</td>
            <td style="color:var(--muted);">{{ $ach->hackathon_name }}</td>
            <td>
                <span class="chip chip-{{ $ach->result === 'Champion' ? 'orange' : ($ach->result === 'Runner-Up' ? 'violet' : 'blue') }}">
                    {{ $ach->result }}
                </span>
            </td>
            <td>
                <div style="display:flex;">
                    @foreach($ach->members->take(3) as $m)
                    <div class="sb-avatar" style="width:24px;height:24px;font-size:8px;margin-left:-6px;border:2px solid var(--surface);">{{ $m->user->initials() }}</div>
                    @endforeach
                    @if($ach->members->count() > 3)
                    <div class="sb-avatar" style="width:24px;height:24px;font-size:8px;margin-left:-6px;border:2px solid var(--surface);background:var(--surface2);">+{{ $ach->members->count()-3 }}</div>
                    @endif
                </div>
            </td>
            <td style="color:var(--muted);font-size:12px;">{{ $ach->achieved_at?->format('M d, Y') }}</td>
            <td>
                <div style="display:flex;gap:6px;">
                    @if($ach->github_link)<a href="{{ $ach->github_link }}" target="_blank" class="btn btn-ghost btn-sm">Git</a>@endif
                    @if($ach->demo_link)<a href="{{ $ach->demo_link }}" target="_blank" class="btn btn-outline btn-sm">Demo</a>@endif
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div style="margin-top:20px;">{{ $all->links() }}</div>
@endif
@endsection