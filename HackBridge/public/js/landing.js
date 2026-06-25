/* =============================================
   HackBridge — Landing Page Scripts
   File: public/js/landing.js
   ============================================= */

/* ── Modal Logic ── */
function openModal(type) {
    const id = type === 'login' ? 'loginModal' : 'signupModal';
    document.getElementById(id).classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeModal(type) {
    const id = type === 'login' ? 'loginModal' : 'signupModal';
    document.getElementById(id).classList.remove('open');
    document.body.style.overflow = '';
}

function switchModal(from, to) {
    closeModal(from);
    setTimeout(() => openModal(to), 180);
}

function backdropClose(e, id) {
    if (e.target.id === id) {
        document.getElementById(id).classList.remove('open');
        document.body.style.overflow = '';
    }
}

/* Close on Escape key */
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        ['loginModal', 'signupModal'].forEach(id => {
            document.getElementById(id).classList.remove('open');
        });
        document.body.style.overflow = '';
    }
});

/* ── Hackathon Tab Switcher ── */
function switchTab(btn, section) {
    document.querySelectorAll('.hack-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('intra').style.display = section === 'intra' ? 'grid' : 'none';
    document.getElementById('inter').style.display = section === 'inter' ? 'grid' : 'none';
}

/* ── Scroll Reveal ── */
const observer = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.classList.add('visible');
            observer.unobserve(e.target);
        }
    });
}, { threshold: 0.12 });

document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

/* ── Live Countdown Timer ── */
function updateCountdown() {
    const target = new Date('2025-07-18T23:59:59');
    const now    = new Date();
    const diff   = target - now;

    if (diff > 0) {
        const days = Math.floor(diff / 86400000);
        const hrs  = Math.floor((diff % 86400000) / 3600000);
        const mins = Math.floor((diff % 3600000) / 60000);
        const nums = document.querySelectorAll('.cd-num');
        if (nums[0]) {
            nums[0].textContent = String(days).padStart(2, '0');
            nums[1].textContent = String(hrs).padStart(2, '0');
            nums[2].textContent = String(mins).padStart(2, '0');
        }
    }
}

updateCountdown();
setInterval(updateCountdown, 30000);