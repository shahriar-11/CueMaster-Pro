/**
 * CueMaster Pro — Global JS
 * Handles: live-running session timers, mobile sidebar toggle
 */

document.addEventListener('DOMContentLoaded', () => {

  /* ---------- Live session timers ---------- */
  const timers = document.querySelectorAll('.live-timer[data-start]');

  function pad(n) { return String(n).padStart(2, '0'); }

  function tick() {
    const now = Date.now();
    timers.forEach(el => {
      const start = new Date(el.dataset.start.replace(' ', 'T')).getTime();
      let diff = Math.max(0, Math.floor((now - start) / 1000));

      const h = Math.floor(diff / 3600);
      const m = Math.floor((diff % 3600) / 60);
      const s = diff % 60;

      el.textContent = `${pad(h)}:${pad(m)}:${pad(s)}`;
    });
  }

  if (timers.length) {
    tick();
    setInterval(tick, 1000);
  }

  /* ---------- Mobile sidebar toggle ---------- */
  const sidebar = document.getElementById('sidebar');
  const toggleBtn = document.getElementById('sidebarToggle');
  const overlay = document.getElementById('sidebarOverlay');

  function closeSidebar() {
    sidebar?.classList.remove('show');
    overlay?.classList.remove('show');
  }

  toggleBtn?.addEventListener('click', () => {
    sidebar?.classList.toggle('show');
    overlay?.classList.toggle('show');
  });

  overlay?.addEventListener('click', closeSidebar);
});
