<script>
    const sidebar = document.querySelector('.sidebar-modern');
    const toggleBtn = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('sidebarOverlay');

    toggleBtn?.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('active');
        document.body.classList.toggle('sidebar-open');
    });

    overlay?.addEventListener('click', closeSidebar);

    document.querySelectorAll('.sidebar-modern .menu-link').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 768) closeSidebar();
        });
    });

    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('active');
        document.body.classList.remove('sidebar-open');
    }
</script>
<?php $system_version = "v1.0"; ?>
<footer class="text-center mt-4 p-3"
    style="background: #f8f9fa; border-top: 1px solid #ddd; font-size: 1rem; color: #198754;">
    <small>
        &copy; <?= date('Y') ?> Dantechdevs IT Consultancy & Company.All rights reserved. | Version
        <?= $system_version ?>
    </small>
</footer>
</body>

</html>