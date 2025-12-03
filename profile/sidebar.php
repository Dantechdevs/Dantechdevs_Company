<?php
/*
================================================================================
File: profile/sidebar.php
Purpose: Modern, Professional Sidebar for Profile Dashboard
Author: Dantechdevs
Features:
    - White sidebar with green active/hover highlights
    - Smooth shadow and hover animations
    - Icons with animated slide effect
    - Mobile slide-in/out with toggle button
================================================================================
*/

$username = $_SESSION['username'] ?? 'User';
$role = $_SESSION['role'] ?? 'Administrator';
?>

<!-- Sidebar Toggle Button (Mobile) -->
<button class="btn btn-success btn-sm d-md-none" id="sidebarToggle"
    style="position:fixed; top:15px; left:15px; z-index:1001;">
    <i class="bi bi-list"></i>
</button>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="brand text-success">Dantechdevs</div>

    <a href="../index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
        <i class="bi bi-speedometer2 me-2"></i> <span>Dashboard</span>
    </a>
    <a href="profile.php" class="<?= basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : '' ?>">
        <i class="bi bi-person-circle me-2"></i> <span>Profile</span>
    </a>
    <a href="settings.php" class="<?= basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : '' ?>">
        <i class="bi bi-gear me-2"></i> <span>Settings</span>
    </a>
    <a href="../logout.php" class="<?= basename($_SERVER['PHP_SELF']) == 'logout.php' ? 'active' : '' ?>">
        <i class="bi bi-box-arrow-right me-2"></i> <span>Logout</span>
    </a>

    <hr style="border-color: #dee2e6; margin: 20px 10px;">

    <div class="text-center text-dark small mt-auto mb-3">
        Logged in as:<br>
        <strong><?= htmlspecialchars($username) ?></strong><br>
        <em><?= htmlspecialchars($role) ?></em>
    </div>
</div>

<style>
    /* ---------- Sidebar Styles ---------- */
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
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
        z-index: 999;
    }

    .sidebar .brand {
        font-size: 1.5rem;
        text-align: center;
        margin-bottom: 25px;
        font-weight: bold;
        letter-spacing: 1px;
    }

    .sidebar a {
        display: flex;
        align-items: center;
        color: #333;
        padding: 12px 20px;
        text-decoration: none;
        font-weight: 500;
        border-radius: 10px;
        margin: 4px 10px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .sidebar a i {
        font-size: 1.2rem;
        transition: transform 0.3s ease;
    }

    .sidebar a span {
        transition: margin-left 0.3s ease;
    }

    .sidebar a:hover,
    .sidebar a.active {
        background: #198754;
        /* Green highlight */
        color: #fff;
        box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
    }

    .sidebar a:hover i,
    .sidebar a.active i {
        color: #fff;
        transform: translateX(5px);
    }

    .sidebar a:hover span,
    .sidebar a.active span {
        margin-left: 5px;
    }

    /* ---------- Mobile Sidebar ---------- */
    @media(max-width:768px) {
        .sidebar {
            transform: translateX(-250px);
            /* hidden by default */
            width: 220px;
            box-shadow: 2px 0 12px rgba(0, 0, 0, 0.1);
        }

        .sidebar.show {
            transform: translateX(0);
            /* slide in */
        }

        .sidebar a span {
            display: inline;
            /* show text when opened */
        }
    }

    /* Sidebar toggle button styling */
    #sidebarToggle {
        border-radius: 50%;
        padding: 10px 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease;
    }

    #sidebarToggle:active {
        transform: scale(0.95);
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebar = document.getElementById('sidebar');
        const toggle = document.getElementById('sidebarToggle');

        toggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });

        // Close sidebar on clicking outside
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768 && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        });
    });
</script>