<?php
// sidebar.php
$activePage = $activePage ?? '';
?>

<div class="sidebar-modern">

    <div class="sidebar-menu">

        <!-- DASHBOARD -->
        <a href="index.php" class="menu-link <?= ($activePage == 'dashboard') ? 'active' : ''; ?>">
            <i class="bi bi-grid-1x2-fill"></i>
            <span>Dashboard</span>
        </a>

        <a href="portal.php" class="menu-link <?= ($activePage == 'portal') ? 'active' : ''; ?>">
            <i class="bi bi-speedometer2"></i>
            <span>My Portal</span>
        </a>

        <a href="users.php" class="menu-link <?= ($activePage == 'users') ? 'active' : ''; ?>">
            <i class="bi bi-people-fill"></i>
            <span>Users & Roles</span>
        </a>

        <!-- MODULES -->
        <div class="menu-title">MODULES</div>

        <a href="projects/project_list.php" class="menu-link <?= ($activePage == 'projects') ? 'active' : ''; ?>">
            <i class="bi bi-kanban-fill"></i>
            <span>Projects</span>
        </a>

        <a href="clients/client_list.php" class="menu-link <?= ($activePage == 'clients') ? 'active' : ''; ?>">
            <i class="bi bi-person-badge-fill"></i>
            <span>Clients</span>
        </a>

        <a href="tasks/task_list.php" class="menu-link <?= ($activePage == 'tasks') ? 'active' : ''; ?>">
            <i class="bi bi-check2-square"></i>
            <span>Tasks</span>
        </a>

        <a href="billing/invoices.php" class="menu-link <?= ($activePage == 'billing') ? 'active' : ''; ?>">
            <i class="bi bi-receipt"></i>
            <span>Accounting</span>
        </a>

        <a href="marketing.php" class="menu-link <?= ($activePage == 'marketing') ? 'active' : ''; ?>">
            <i class="bi bi-megaphone-fill"></i>
            <span>Marketing</span>
        </a>

        <a href="hr/" class="menu-link <?= ($activePage == 'hr') ? 'active' : ''; ?>">
            <i class="bi bi-people"></i>
            <span>Human Resources</span>
        </a>

        <a href="reports/" class="menu-link <?= ($activePage == 'reports') ? 'active' : ''; ?>">
            <i class="bi bi-graph-up-arrow"></i>
            <span>Reports</span>
        </a>

        <!-- SYSTEM -->
        <div class="menu-title">SYSTEM</div>

        <a href="settings.php" class="menu-link <?= ($activePage == 'settings') ? 'active' : ''; ?>">
            <i class="bi bi-gear-fill"></i>
            <span>System Settings</span>
        </a>

        <a href="activity_logs.php" class="menu-link <?= ($activePage == 'activity') ? 'active' : ''; ?>">
            <i class="bi bi-clock-history"></i>
            <span>Activity Logs</span>
        </a>

        <a href="account_logs.php" class="menu-link <?= ($activePage == 'accounts') ? 'active' : ''; ?>">
            <i class="bi bi-shield-lock-fill"></i>
            <span>Account Logs</span>
        </a>

        <!-- LOGOUT -->
        <a href="auth/logout.php" class="menu-link logout">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
        </a>

    </div>
</div>