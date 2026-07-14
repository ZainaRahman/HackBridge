@extends('layouts.app')
@section('title','Admin Dashboard')
@section('page-title','Admin Dashboard')

@section('content')

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon si-blue">👥</div>
        <div><div class="stat-val">{{ $stats['users'] }}</div><div class="stat-lbl">Total Users</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-orange">🏆</div>
        <div><div class="stat-val">{{ $stats['hackathons'] }}</div><div class="stat-lbl">Hackathons</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-violet">🌟</div>
        <div><div class="stat-val">{{ $stats['achievements'] }}</div><div class="stat-lbl">Achievements</div></div>
    </div>
</div>

<div class="grid-2" style="margin-bottom:20px;">

    {{-- Quick Links --}}
    <div class="card">
        <div class="sec-header">
            <div class="sec-title">⚙️ Manage</div>
        </div>
        <div style="display:flex;flex-direction:column;gap:10px;">
            <a href="{{ route('admin.hackathons') }}" class="btn btn-outline btn-sm" style="justify-content:space-between;">
                🏆 Manage Hackathons <span>→</span>
            </a>
            <a href="{{ route('admin.achievements') }}" class="btn btn-outline btn-sm" style="justify-content:space-between;">
                🌟 Manage Hall of Fame <span>→</span>
            </a>
        </div>
    </div>

    {{-- Recent Users --}}
    <div class="card">
        <div class="sec-header">
            <div class="sec-title">🆕 Recently Joined</div>
        </div>
        @forelse($recentUsers as $u)
        <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--border);">
            <div class="sb-avatar" style="width:32px;height:32px;font-size:11px;flex-shrink:0;">{{ $u->initials() }}</div>
            <div style="flex:1;">
                <div style="font-size:13px;font-weight:600;color:var(--white);">{{ $u->name }}</div>
                <div style="font-size:11px;color:var(--muted);">{{ $u->email }}</div>
            </div>
            <div style="font-size:11px;color:var(--muted);">{{ $u->created_at->diffForHumans() }}</div>
        </div>
        @empty
        <div class="empty-state" style="padding:20px;"><span class="es-icon">👤</span><p>No users yet</p></div>
        @endforelse
    </div>

</div>

@endsection