<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>HackBridge — Build Your Dream Team</title>

{{-- Google Fonts --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

{{-- Landing Page CSS --}}
<link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
@php
    // Decide whether to auto-open a modal on page load, and which one.
    // Priority: validation errors first (so a failed login/signup reopens the right
    // form with the error shown), then falls back to the route itself — this covers
    // someone landing on /login or /register directly (e.g. redirected here by the
    // `auth` middleware after trying to visit a protected page while logged out).
    $autoOpenModal = null;
    if ($errors->any()) {
        $autoOpenModal = old('first_name') ? 'signup' : 'login';
    } elseif (request()->routeIs('login')) {
        $autoOpenModal = 'login';
    } elseif (request()->routeIs('register')) {
        $autoOpenModal = 'signup';
    }
@endphp
@if($autoOpenModal)
<script>
document.addEventListener('DOMContentLoaded', function() {
    openModal('{{ $autoOpenModal }}');
});
</script>
@endif
@if ($errors->any())
<div style="position:fixed;top:20px;right:20px;background:#EF4444;color:white;padding:12px 20px;border-radius:8px;z-index:9999;">
    @foreach ($errors->all() as $error)
        <div>{{ $error }}</div>
    @endforeach
</div>
@endif
<body>

{{-- ── Ambient Orbs ── --}}
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>

{{-- ── NAVIGATION ── --}}
<nav>
    <a href="{{ url('/') }}" class="nav-logo">
        <div class="logo-mark">H</div>
        <span class="logo-text">Hack<span>Bridge</span></span>
    </a>

    <ul class="nav-links">
        <li><a href="#about">About</a></li>
        <li><a href="#features">Features</a></li>
        <li><a href="#hackathons">Hackathons</a></li>
        
    </ul>

    <div class="nav-actions">
        <button class="btn-nav-outline" onclick="openModal('login')">Sign In</button>
        <button class="btn-nav-primary" onclick="openModal('signup')">Join Free</button>
    </div>
</nav>

{{-- ── HERO ── --}}
<section class="hero" id="about">
    <div class="hero-badge">🔥 Built for BD University Students</div>

    <h1 class="hero-title">
        <span class="line-1">Find your team.</span>
        <span class="line-2">Win together.</span>
    </h1>

    <p class="hero-sub">
        HackBridge connects university students with the right teammates for hackathons,
        competitions, and projects — based on skills, department, and chemistry.
    </p>

    <div class="hero-cta">
        <button class="btn-hero-primary" onclick="openModal('signup')">
            Build Your Team →
        </button>
        <button class="btn-hero-ghost" onclick="document.getElementById('features').scrollIntoView({behavior:'smooth'})">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><polygon points="10,8 16,12 10,16"/>
            </svg>
            See how it works
        </button>
    </div>

    {{-- Floating Preview Cards --}}
    <div class="hero-visual">
        <div class="float-card float-card-1">
            <div class="fc-label">🔍 Smart Match</div>
            <div class="fc-title">Rafi Ahmed</div>
            <div class="skill-chips">
                <span class="chip chip-blue">ML</span>
                <span class="chip chip-violet">Python</span>
                <span class="chip chip-green">TensorFlow</span>
            </div>
            <div class="match-bar">
                <div class="match-label">
                    <span>Skill match</span>
                    <span style="color:#22C55E;font-weight:700;">92%</span>
                </div>
                <div class="bar-track"><div class="bar-fill" style="width:92%"></div></div>
            </div>
        </div>

        <div class="float-card float-card-2">
            <div class="fc-label">🏆 NASA Space Apps 2025</div>
            <div class="fc-title">Team ByteForce — Champions</div>
            <div class="skill-chips">
                <span class="chip chip-cyan">React</span>
                <span class="chip chip-blue">Laravel</span>
                <span class="chip chip-violet">ML</span>
                <span class="chip chip-green">IoT</span>
            </div>
            <div class="avatar-row">
                <div class="avatar av-blue">ZR</div>
                <div class="avatar av-violet">MH</div>
                <div class="avatar av-cyan">TA</div>
                <div class="avatar av-green">SA</div>
                <div class="avatar" style="background:var(--surface);color:var(--muted);font-size:9px;">+2</div>
            </div>
        </div>

        <div class="float-card float-card-3">
            <div class="fc-label">⏰ Deadline</div>
            <div class="fc-title">KUET CSE Fest 2025</div>
            <div class="countdown-row">
                <div class="cd-block"><div class="cd-num">04</div><div class="cd-unit">Days</div></div>
                <div class="cd-block"><div class="cd-num">12</div><div class="cd-unit">Hrs</div></div>
                <div class="cd-block"><div class="cd-num">38</div><div class="cd-unit">Min</div></div>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="stats-row" >
        <div class="stat-item">
            <div class="stat-num">840<span>+</span></div>
            <div class="stat-desc">Students Registered</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">120<span>+</span></div>
            <div class="stat-desc">Teams Formed</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">38<span>+</span></div>
            <div class="stat-desc">Hackathons Listed</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">6<span>+</span></div>
            <div class="stat-desc">Universities</div>
        </div>
    </div>
</section>

<div class="divider"></div>

{{-- ── FEATURES ── --}}
<section class="section" id="features">
    <div class="reveal">
        <div class="section-eyebrow">Features</div>
        <h2 class="section-title">Everything you need<br>to build a winning team</h2>
        <p class="section-sub">From finding your first teammate to winning your first hackathon — HackBridge has every tool you need.</p>
    </div>

    <div class="features-grid reveal">
        <div class="feat-card wide">
            <div class="feat-icon fi-blue">🎯</div>
            <div class="feat-title">Smart Team Matching</div>
            <div class="feat-desc">Post your project's required skills and our algorithm ranks students by match score, availability, and department preference. Same-dept or cross-dept (CSE + EEE for robotics) — you choose.</div>
            <div class="feat-tags">
                <span class="feat-tag">Skill Gap Analysis</span>
                <span class="feat-tag">Chemistry Score</span>
                <span class="feat-tag">Dept Filter</span>
                <span class="feat-tag">Match %</span>
            </div>
        </div>

        <div class="feat-card">
            <div class="feat-icon fi-violet">🏆</div>
            <div class="feat-title">Hackathon Board</div>
            <div class="feat-desc">Intra-university (KUET only) and national/international listings with countdown timers and a direct "Find Team" button per hackathon.</div>
        </div>

        <div class="feat-card">
            <div class="feat-icon fi-cyan">📋</div>
            <div class="feat-title">Team Workspace</div>
            <div class="feat-desc">Kanban board, role assignments, shared links (GitHub, Drive, Figma), announcements and meeting notes — all in one private space.</div>
        </div>

        <div class="feat-card">
            <div class="feat-icon fi-green">🌟</div>
            <div class="feat-title">Hall of Fame</div>
            <div class="feat-desc">Top teams showcased with achievements, individual contributions, and public project pages with demo links and tech stack.</div>
        </div>

        <div class="feat-card">
            <div class="feat-icon fi-orange">🤝</div>
            <div class="feat-title">Skill Endorsements</div>
            <div class="feat-desc">After project completion, teammates endorse each other's skills. Achievement badges like Champion 🏆 and Best UI 🎨 appear on your public profile.</div>
        </div>

        <div class="feat-card">
            <div class="feat-icon fi-pink">👨‍🏫</div>
            <div class="feat-title">Senior Mentor Connect</div>
            <div class="feat-desc">4th year and Masters students offer mentorship. Junior teams request a mentor. Ghost member flagging prevents inactive teammates from killing your project.</div>
        </div>
    </div>
</section>

<div class="divider"></div>

{{-- ── HACKATHON BOARD PREVIEW ── --}}
<section class="hack-preview" id="hackathons" style="padding-top:80px;">
    <div class="reveal" style="margin-bottom:32px;">
        <div class="section-eyebrow">Hackathon Board</div>
        <h2 class="section-title">Never miss a competition</h2>
        <p class="section-sub">Two sections — KUET-internal and national/global. Every listing has a "Find Team for This" button.</p>
    </div>

    <div class="hack-tabs reveal">
        <button class="hack-tab active" onclick="switchTab(this,'intra')">🏫 Intra-University</button>
        <button class="hack-tab" onclick="switchTab(this,'inter')">🌐 National / International</button>
    </div>

    {{-- Intra-University --}}
    <div class="hack-grid reveal" id="intra">
        <div class="hack-card">
            <div class="hack-banner" style="background:linear-gradient(135deg,#1e3a5f,#0f2542);">🖥️</div>
            <div class="hack-body">
                <div class="hack-name">KUET CSE Fest 2025</div>
                <div class="hack-org">Dept of CSE, KUET · Khulna</div>
                <div class="hack-chips">
                    <span class="chip chip-blue">Intra-KUET</span>
                    <span class="chip chip-cyan">Team (2–4)</span>
                    <span class="chip chip-green">AI / Web</span>
                </div>
                <div class="hack-footer">
                    <div class="hack-deadline">Deadline: <strong>Jul 18, 2025</strong></div>
                    <button class="btn-find-team" onclick="openModal('signup')">Find Team →</button>
                </div>
            </div>
        </div>

        <div class="hack-card">
            <div class="hack-banner" style="background:linear-gradient(135deg,#2d1b5e,#1a0f3d);">⚡</div>
            <div class="hack-body">
                <div class="hack-name">EEE Innovation Cup</div>
                <div class="hack-org">Dept of EEE, KUET · Khulna</div>
                <div class="hack-chips">
                    <span class="chip chip-blue">Intra-KUET</span>
                    <span class="chip chip-violet">Team (3–5)</span>
                    <span class="chip chip-cyan">Hardware</span>
                </div>
                <div class="hack-footer">
                    <div class="hack-deadline">Deadline: <strong>Aug 2, 2025</strong></div>
                    <button class="btn-find-team" onclick="openModal('signup')">Find Team →</button>
                </div>
            </div>
        </div>

        <div class="hack-card">
            <div class="hack-banner" style="background:linear-gradient(135deg,#0f3d2d,#062018);">🤖</div>
            <div class="hack-body">
                <div class="hack-name">K-MiNDS Datathon</div>
                <div class="hack-org">K-MiNDS Club, KUET</div>
                <div class="hack-chips">
                    <span class="chip chip-blue">Intra-KUET</span>
                    <span class="chip chip-green">Solo / Team</span>
                    <span class="chip chip-violet">Data Science</span>
                </div>
                <div class="hack-footer">
                    <div class="hack-deadline">Deadline: <strong>Aug 15, 2025</strong></div>
                    <button class="btn-find-team" onclick="openModal('signup')">Find Team →</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Inter-University --}}
    <div class="hack-grid reveal" id="inter" style="display:none;">
        <div class="hack-card">
            <div class="hack-banner" style="background:linear-gradient(135deg,#1a3a6b,#0c2044);">🚀</div>
            <div class="hack-body">
                <div class="hack-name">NASA Space Apps 2025</div>
                <div class="hack-org">NASA · International · Online</div>
                <div class="hack-chips">
                    <span class="chip chip-blue">International</span>
                    <span class="chip chip-cyan">Team (2–6)</span>
                    <span class="chip chip-green">Open Theme</span>
                </div>
                <div class="hack-footer">
                    <div class="hack-deadline">Deadline: <strong>Oct 4, 2025</strong></div>
                    <button class="btn-find-team" onclick="openModal('signup')">Find Team →</button>
                </div>
            </div>
        </div>

        <div class="hack-card">
            <div class="hack-banner" style="background:linear-gradient(135deg,#3d2000,#241300);">🧠</div>
            <div class="hack-body">
                <div class="hack-name">ICPC Asia Dhaka</div>
                <div class="hack-org">ICPC · National · Offline</div>
                <div class="hack-chips">
                    <span class="chip chip-violet">National</span>
                    <span class="chip chip-blue">Team (3)</span>
                    <span class="chip chip-cyan">CP / Algorithms</span>
                </div>
                <div class="hack-footer">
                    <div class="hack-deadline">Deadline: <strong>Sep 20, 2025</strong></div>
                    <button class="btn-find-team" onclick="openModal('signup')">Find Team →</button>
                </div>
            </div>
        </div>

        <div class="hack-card">
            <div class="hack-banner" style="background:linear-gradient(135deg,#0d3d1a,#061f0c);">🌍</div>
            <div class="hack-body">
                <div class="hack-name">Google Solution Challenge</div>
                <div class="hack-org">Google · International · Online</div>
                <div class="hack-chips">
                    <span class="chip chip-green">International</span>
                    <span class="chip chip-blue">Team (2–4)</span>
                    <span class="chip chip-cyan">SDG / App Dev</span>
                </div>
                <div class="hack-footer">
                    <div class="hack-deadline">Deadline: <strong>Mar 1, 2026</strong></div>
                    <button class="btn-find-team" onclick="openModal('signup')">Find Team →</button>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── CTA ── --}}
<section class="cta-section">
    <div class="cta-box reveal">
        <h2 class="cta-title">Your next championship<br>team is one click away.</h2>
        <p class="cta-sub">Join 840+ KUET and BD university students already building, competing, and winning together on HackBridge.</p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
            <button class="btn-hero-primary" onclick="openModal('signup')">Create Your Profile →</button>
            <button class="btn-hero-ghost" onclick="openModal('login')">Sign In</button>
        </div>
    </div>
</section>

{{-- ── FOOTER ── --}}
<footer>
    <div style="display:flex;align-items:center;gap:10px;">
        <div class="logo-mark" style="width:28px;height:28px;font-size:12px;">H</div>
        <span class="footer-copy">© {{ date('Y') }} HackBridge · Built for BD students</span>
    </div>
    <div class="footer-links">
        <a href="#">About</a>
        <a href="#">Contact</a>
        <a href="#">Privacy</a>
        <a href="#">KUET</a>
    </div>
</footer>

{{-- ── SIGN IN MODAL ── --}}
<div class="modal-backdrop" id="loginModal" onclick="backdropClose(event,'loginModal')">
    <div class="modal-box">
        <button class="modal-close-btn" onclick="closeModal('login')">✕</button>
        <div class="modal-logo">
            <div class="modal-logo-mark">H</div>
            <span class="modal-logo-text">Hack<span>Bridge</span></span>
        </div>
        <h2 class="modal-title">Welcome back</h2>
        <p class="modal-sub">Sign in to your account to continue building your team.</p>

        <form method="POST" action="/login">
            @csrf
            <div class="form-group-m">
                <label class="form-label-m">University Email</label>
                <input type="email" name="email" class="form-input" placeholder="you@kuet.ac.bd" required>
            </div>
            <div class="form-group-m">
                <label class="form-label-m">Password</label>
                <input type="password" name="password" class="form-input" placeholder="••••••••" required>
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
                <label style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--muted,#888);cursor:pointer;">
                    <input type="checkbox" name="remember" value="1" style="width:auto;">
                    Remember me
                </label>
                @if(Route::has('password.request'))
                    <a href="/forgot-password" style="font-size:12px;color:var(--blue);text-decoration:none;font-weight:600;">Forgot password?</a>
                @endif
            </div>
            <button type="submit" class="btn-modal-submit">Sign In to HackBridge</button>
        </form>

        <div class="form-divider"><span>OR</span></div>
        <div class="modal-switch">
            New to HackBridge? <a onclick="switchModal('login','signup')">Create account</a>
        </div>
    </div>
</div>

{{-- ── SIGN UP MODAL ── --}}
<div class="modal-backdrop" id="signupModal" onclick="backdropClose(event,'signupModal')">
    <div class="modal-box">
        <button class="modal-close-btn" onclick="closeModal('signup')">✕</button>
        <div class="modal-logo">
            <div class="modal-logo-mark">H</div>
            <span class="modal-logo-text">Hack<span>Bridge</span></span>
        </div>
        <h2 class="modal-title">Join HackBridge</h2>
        <p class="modal-sub">Create your profile and start finding teammates today.</p>

        <form method="POST" action="/register">
            @csrf
            <div class="form-row">
                <div class="form-group-m">
                    <label class="form-label-m">First Name</label>
                    <input type="text" name="first_name" class="form-input" placeholder="Zaina" required>
                </div>
                <div class="form-group-m">
                    <label class="form-label-m">Last Name</label>
                    <input type="text" name="last_name" class="form-input" placeholder="Rahman" required>
                </div>
            </div>
            <div class="form-group-m">
                <label class="form-label-m">University Email</label>
                <input type="email" name="email" class="form-input" placeholder="you@kuet.ac.bd" required>
            </div>
            <div class="form-row">
                <div class="form-group-m">
                    <label class="form-label-m">Department</label>
                    <select name="department" class="form-input" required>
                        <option value="">Select dept</option>
                        <option value="CSE">CSE</option>
                        <option value="EEE">EEE</option>
                        <option value="ME">ME</option>
                        <option value="CE">CE</option>
                        <option value="ECE">ECE</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group-m">
                    <label class="form-label-m">Year</label>
                    <select name="year" class="form-input" required>
                        <option value="">Select year</option>
                        <option value="1">1st Year</option>
                        <option value="2">2nd Year</option>
                        <option value="3">3rd Year</option>
                        <option value="4">4th Year</option>
                        <option value="5">Masters</option>
                    </select>
                </div>
            </div>

            <div class="form-group-m">
                <label class="form-label-m">Password</label>
                <input type="password" name="password" class="form-input" placeholder="Create a strong password" required>
            </div>
            <div class="form-group-m">
                <label class="form-label-m">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-input" placeholder="Confirm your password" required>
            </div>
            <button type="submit" class="btn-modal-submit">Create My Profile →</button>
        </form>

        <div class="modal-switch">
            Already have an account? <a onclick="switchModal('signup','login')">Sign in</a>
        </div>
    </div>
</div>

{{-- Landing Page JS --}}
<script src="{{ asset('js/landing.js') }}"></script>

</body>
</html>