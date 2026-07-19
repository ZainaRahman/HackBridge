// Auto-dismiss alerts after 3.5s
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => {
            el.style.transition = 'opacity .4s, transform .4s';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-8px)';
            setTimeout(() => el.remove(), 400);
        });
    }, 3500);
});

// Mobile sidebar close on outside click
document.addEventListener('click', (e) => {
    const sidebar = document.getElementById('sidebar');
    const menuBtn = document.querySelector('.menu-btn');
    if (sidebar && sidebar.classList.contains('open')) {
        if (!sidebar.contains(e.target) && e.target !== menuBtn) {
            sidebar.classList.remove('open');
        }
    }
});