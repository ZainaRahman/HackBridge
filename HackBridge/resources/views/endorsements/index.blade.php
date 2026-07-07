@extends('layouts.app')
@section('title','My Endorsements')
@section('page-title','Skill Endorsements')

@section('content')
<div class="page-header">
    <h2>⭐ My Endorsements</h2>
    <p>Skills endorsed by your teammates after project completion</p>
</div>

@if($endorsements->isEmpty())
<div class="empty-state card">
    <span class="es-icon">⭐</span>
    <p>No endorsements yet. Complete a project and ask teammates to endorse your skills!</p>
</div>
@else
<div class="grid-3">
@foreach($endorsements->groupBy('skill_id') as $skillId => $group)
<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
        <div style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;color:var(--white);">{{ $group->first()->skill->name }}</div>
        <span class="chip chip-blue">{{ $group->count() }} {{ Str::plural('endorsement', $group->count()) }}</span>
    </div>
    <div style="font-size:11px;color:var(--muted);margin-bottom:12px;">{{ $group->first()->skill->category }}</div>
    @foreach($group as $endorsement)
    <div style="display:flex;align-items:flex-start;gap:8px;padding:8px 0;border-bottom:1px solid var(--border);">
        <div class="sb-avatar" style="width:28px;height:28px;font-size:10px;flex-shrink:0;">{{ $endorsement->endorser->initials() }}</div>
        <div>
            <div style="font-size:12px;font-weight:600;color:var(--white);">{{ $endorsement->endorser->name }}</div>
            @if($endorsement->note)
            <div style="font-size:11px;color:var(--muted);margin-top:2px;font-style:italic;">"{{ $endorsement->note }}"</div>
            @endif
            <div style="font-size:10px;color:var(--muted);margin-top:2px;">{{ $endorsement->created_at->diffForHumans() }}</div>
        </div>
    </div>
    @endforeach
</div>
@endforeach
</div>
@endif
@endsection