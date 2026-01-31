<?php
$activePage = $activePage ?? '';
?>

<!-- MOBILE TOGGLE BUTTON (put inside topbar/header) -->
<button id="sidebarToggle" class="sidebar-toggle d-md-none" aria-label="Toggle sidebar">
    <i class="bi bi-list"></i>
</button>

<!-- OVERLAY -->
<div id="sidebarOverlay" class="sidebar-overlay"></div>

<!-- SIDEBAR -->
<aside class="sidebar-modern">

    <div class="sidebar-menu">

        <!-- DASHBOARD -->
        <a href="dashboard.php" class="menu-link <?= ($activePage === 'dashboard') ? 'active' : ''; ?>">
            <i class="bi bi-grid-1x2-fill"></i>
            <span>Dashboard</span>
        </a>

        <a href="profile/profile.php" class="menu-link <?= ($activePage === 'portal') ? 'active' : ''; ?>">
            <i class="bi bi-speedometer2"></i>
            <span>My Portal</span>
        </a>

        <a href="users.php" class="menu-link <?= ($activePage === 'users') ? 'active' : ''; ?>">
            <i class="bi bi-people-fill"></i>
            <span>Users & Roles</span>
        </a>

        <!-- MODULES -->
        <div class="menu-title">Modules</div>

        <a href="pages/projects/project_list.php"
            class="menu-link <?= ($activePage === 'projects') ? 'active' : ''; ?>">
            <i class="bi bi-kanban-fill"></i>
            <span>Projects</span>
        </a>

        <a href="pages/clients/client_list.php" class="menu-link <?= ($activePage === 'clients') ? 'active' : ''; ?>">
            <i class="bi bi-person-badge-fill"></i>
            <span>Clients</span>
        </a>

        <a href="tasks/task_list.php" class="menu-link <?= ($activePage === 'tasks') ? 'active' : ''; ?>">
            <i class="bi bi-check2-square"></i>
            <span>Tasks</span>
        </a>

        <a href="payments/payment_dashboard.php" class="menu-link <?= ($activePage === 'payments') ? 'active' : ''; ?>">
            <i class="bi bi-receipt"></i>
            <span>Accounting</span>
        </a>

        <a href="marketing.php" class="menu-link <?= ($activePage === 'marketing') ? 'active' : ''; ?>">
            <i class="bi bi-megaphone-fill"></i>
            <span>Marketing</span>
        </a>

        <a href="hr/" class="menu-link <?= ($activePage === 'hr') ? 'active' : ''; ?>">
            <i class="bi bi-people"></i>
            <span>Human Resources</span>
        </a>

        <a href="reports/" class="menu-link <?= ($activePage === 'reports') ? 'active' : ''; ?>">
            <i class="bi bi-graph-up-arrow"></i>
            <span>Reports</span>
        </a>

        <!-- SYSTEM -->
        <div class="menu-title">System</div>

        <a href="settings.php" class="menu-link <?= ($activePage === 'settings') ? 'active' : ''; ?>">
            <i class="bi bi-gear-fill"></i>
            <span>System Settings</span>
        </a>

        <a href="activity_logs.php" class="menu-link <?= ($activePage === 'activity') ? 'active' : ''; ?>">
            <i class="bi bi-clock-history"></i>
            <span>Activity Logs</span>
        </a>

        <a href="account_logs.php" class="menu-link <?= ($activePage === 'accounts') ? 'active' : ''; ?>">
            <i class="bi bi-shield-lock-fill"></i>
            <span>Account Logs</span>
        </a>

        <!-- LOGOUT -->
        <a href="logout.php" class="menu-link logout">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
        </a>

    </div>
</aside>