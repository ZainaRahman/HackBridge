<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>HackBridge — @yield('title','Dashboard')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@stack('styles')
</head>
<body>

<aside class="sidebar" id="sidebar">
    <div class="sb-brand">
        <div class="logo-mark">H</div>
        <span class="logo-text">Hack<span>Bridge</span></span>
    </div>

    <nav class="sb-nav">
        <div class="sb-section">Overview</div>
        <a href="{{ route('dashboard') }}"       class="sb-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="sb-icon">⬡</span> Dashboard
        </a>
        <a href="{{ route('hackathons.index') }}" class="sb-link {{ request()->routeIs('hackathons.*') ? 'active' : '' }}">
            <span class="sb-icon">🏆</span> Hackathons
        </a>
        <a href="{{ route('halloffame.index') }}" class="sb-link {{ request()->routeIs('halloffame.*') ? 'active' : '' }}">
            <span class="sb-icon">🥇</span> Hall of Fame
        </a>

        <div class="sb-section">Teams</div>
        <a href="{{ route('projects.index') }}"  class="sb-link {{ request()->routeIs('projects.index') ? 'active' : '' }}">
            <span class="sb-icon">📋</span> Find Projects
        </a>
        <a href="{{ route('projects.create') }}" class="sb-link {{ request()->routeIs('projects.create') ? 'active' : '' }}">
            <span class="sb-icon">＋</span> Post Project
        </a>
        <a href="{{ route('projects.mine') }}"   class="sb-link {{ request()->routeIs('projects.mine') ? 'active' : '' }}">
            <span class="sb-icon">◈</span> My Projects
        </a>
        <a href="{{ route('members.index') }}"   class="sb-link {{ request()->routeIs('members.*') ? 'active' : '' }}">
            <span class="sb-icon">👥</span> Browse Members
        </a>

        <div class="sb-section">Community</div>
        <a href="{{ route('mentors.index') }}"      class="sb-link {{ request()->routeIs('mentors.*') ? 'active' : '' }}">
            <span class="sb-icon">👨‍🏫</span> Mentor Connect
        </a>
        <a href="{{ route('endorsements.index') }}" class="sb-link {{ request()->routeIs('endorsements.*') ? 'active' : '' }}">
            <span class="sb-icon">⭐</span> My Endorsements
        </a>

        @if(auth()->user()->is_admin)
        <div class="sb-section">Admin</div>
        <a href="{{ route('admin.dashboard') }}"   class="sb-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
            <span class="sb-icon">⚙️</span> Admin Panel
        </a>
        @endif
    </nav>

    <div class="sb-footer">
        <a href="{{ route('profile.edit') }}" class="sb-user">
            <div class="sb-avatar">{{ auth()->user()->initials() }}</div>
            <div class="sb-user-info">
                <div class="sb-user-name">{{ auth()->user()->name }}</div>
                <div class="sb-user-dept">{{ auth()->user()->department }} · {{ auth()->user()->university }}</div>
            </div>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sb-logout">Sign Out →</button>
        </form>
    </div>
</aside>

<div class="app-main">
    <header class="topbar">
        <div class="topbar-left">
            <button class="menu-btn" onclick="document.getElementById('sidebar').classList.toggle('open')">☰</button>
            <h1 class="topbar-title">@yield('page-title','Dashboard')</h1>
        </div>
        <div class="topbar-right">
            <span class="avail-badge" style="background:{{ auth()->user()->availabilityColor() }}20;color:{{ auth()->user()->availabilityColor() }};border:1px solid {{ auth()->user()->availabilityColor() }}40;">
                ● {{ auth()->user()->availabilityLabel() }}
            </span>
            <a href="{{ route('profile.edit') }}" class="topbar-avatar">{{ auth()->user()->initials() }}</a>
        </div>
    </header>

    <div class="alerts-wrap">
        @if(session('success'))
            <div class="alert alert-success">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">✕ {{ session('error') }}</div>
        @endif
    </div>

    <main class="app-content">
        @yield('content')
    </main>
</div>

<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>