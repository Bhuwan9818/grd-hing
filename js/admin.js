/* G.R.D Hing — Admin panel interactions */
document.addEventListener('DOMContentLoaded', function () {
  var toggle = document.getElementById('adminMenuToggle');
  var sidebar = document.getElementById('adminSidebar');
  if (toggle && sidebar) {
    toggle.addEventListener('click', function () {
      sidebar.classList.toggle('open');
    });
    document.addEventListener('click', function (e) {
      if (window.innerWidth > 960) return;
      if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
        sidebar.classList.remove('open');
      }
    });
  }
});
