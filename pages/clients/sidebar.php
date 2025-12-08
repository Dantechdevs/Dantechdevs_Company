<?php
$username = $_SESSION['username'] ?? 'User';
$role = $_SESSION['role'] ?? 'Administrator';
?>

<!-- Mobile Sidebar Toggle -->
<button class="btn btn-success btn-sm d-md-none" id="sidebarToggle"
    style="position:fixed; top:15px; left:15px; z-index:1001;">
    <i class="bi bi-list"></i>
</button>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="brand text-success">Clients</div>

    <a href="../index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
        <i class="bi bi-speedometer2 me-2"></i> <span>Dashboard</span>
    </a>

    <a href="client_list.php" class="<?= basename($_SERVER['PHP_SELF']) == 'client_list.php' ? 'active' : '' ?>">
        <i class="bi bi-people-fill me-2"></i> <span>Clients</span>
    </a>

    <a href="client_new.php" class="<?= basename($_SERVER['PHP_SELF']) == 'client_new.php' ? 'active' : '' ?>">
        <i class="bi bi-person-plus-fill me-2"></i> <span>Add Client</span>
    </a>

    <a href="client_import.php" class="<?= basename($_SERVER['PHP_SELF']) == 'client_import.php' ? 'active' : '' ?>">
        <i class="bi bi-upload me-2"></i> <span>Import</span>
    </a>

    <a href="client_export.php" class="<?= basename($_SERVER['PHP_SELF']) == 'client_export.php' ? 'active' : '' ?>">
        <i class="bi bi-download me-2"></i> <span>Export</span>
    </a>

    <a href="deleted_clients.php"
        class="<?= basename($_SERVER['PHP_SELF']) == 'deleted_clients.php' ? 'active' : '' ?>">
        <i class="bi bi-trash me-2"></i> <span>Recycle Bin</span>
    </a>

    <hr style="border-color: #dee2e6; margin: 20px 10px;">

    <a href="../logout.php" class="btn btn-danger mx-3 mt-auto mb-3 text-white">
        <i class="bi bi-box-arrow-right me-2"></i> Logout
    </a>
</div>

<style>
/* Sidebar (desktop visible) */
.sidebar {
    width: 220px;
    height: 100vh;
    background: #fff;
    color: #333;
    position: fixed;
    top: 0;
    left: 0;
    padding-top: 20px;
    border-right: 1px solid #dee2e6;
    display: flex;
    flex-direction: column;
    box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
    z-index: 999;
    transform: translateX(0);
    /* FIX: visible on desktop */
    transition: transform 0.3s ease;
}

/* Sidebar brand text */
.sidebar .brand {
    font-size: 1.5rem;
    text-align: center;
    margin-bottom: 25px;
    font-weight: bold;
    letter-spacing: 1px;
}

/* Sidebar links */
.sidebar a {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    margin: 4px 10px;
    color: #333;
    text-decoration: none;
    font-weight: 500;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.sidebar a i {
    font-size: 1.2rem;
    transition: transform 0.3s ease;
}

.sidebar a:hover,
.sidebar a.active {
    background: #198754;
    /* same green */
    color: #fff;
    box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
}

.sidebar a:hover i,
.sidebar a.active i {
    transform: translateX(5px);
    color: #fff;
}

.sidebar a span {
    transition: margin-left 0.3s ease;
}

.sidebar a:hover span,
.sidebar a.active span {
    margin-left: 5px;
}

/* MOBILE: Sidebar starts hidden */
@media(max-width:768px) {
    .sidebar {
        transform: translateX(-250px);
    }

    .sidebar.show {
        transform: translateX(0);
    }
}

/* Toggle button style */
#sidebarToggle {
    border-radius: 50%;
    padding: 10px 12px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const sidebar = document.getElementById('sidebar');
    const toggle = document.getElementById('sidebarToggle');

    toggle.addEventListener('click', function() {
        sidebar.classList.toggle('show');
    });

    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768 &&
            !sidebar.contains(e.target) &&
            !toggle.contains(e.target)) {
            sidebar.classList.remove('show');
        }
    });
});
</script>