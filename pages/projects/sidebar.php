<?php
/*
================================================================================
File: projects/sidebar.php
Purpose: Sidebar Navigation for Projects Module Only
Author: Dantechdevs
Description:
    - Minimal sidebar for project pages
    - Highlights active page
    - Links to main dashboard, profile, and logout
================================================================================
*/
$activePage = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar sidebar-modern">

    <!-- Dashboard / Main Access -->
    <div class="sidebar-menu">
        <a href="../../index.php" class="menu-link">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <a href="../../profile/profile.php" class="menu-link">
            <i class="bi bi-person-circle"></i> Profile
        </a>
    </div>

    <!-- Projects Module -->
    <span class="menu-title">Projects</span>

    <div class="sidebar-menu">

        <!-- ✅ All Projects -->
        <a href="project_list.php" class="menu-link <?= ($activePage == 'project_list.php') ? 'active' : ''; ?>">
            <i class="bi bi-kanban-fill"></i> All Projects
        </a>

        <!-- ✅ New Project -->
        <a href="project_new.php" class="menu-link <?= ($activePage == 'project_new.php') ? 'active' : ''; ?>">
            <i class="bi bi-plus-circle"></i> New Project
        </a>

        <!-- ✅ Paid Projects -->
        <a href="project_paid.php" class="menu-link <?= ($activePage == 'project_paid.php') ? 'active' : ''; ?>">
            <i class="bi bi-cash-coin"></i> Paid Projects
        </a>

        <!-- ✅ Unpaid / Partial Projects -->
        <a href="project_unpaid.php" class="menu-link <?= ($activePage == 'project_unpaid.php') ? 'active' : ''; ?>">
            <i class="bi bi-exclamation-circle"></i> Unpaid Projects
        </a>

        <!-- ✅ View Project -->
        <a href="project_view.php" class="menu-link <?= ($activePage == 'project_view.php') ? 'active' : ''; ?>">
            <i class="bi bi-eye-fill"></i> View Project
        </a>

        <!-- ✅ Edit Project -->
        <a href="project_edit.php" class="menu-link <?= ($activePage == 'project_edit.php') ? 'active' : ''; ?>">
            <i class="bi bi-pencil-fill"></i> Edit Project
        </a>

        <!-- ✅ FIXED: Deleted Projects -->
        <a href="deleted_projects.php" class="menu-link <?= ($activePage == 'deleted_projects.php') ? 'active' : ''; ?>">
            <i class="bi bi-trash-fill"></i> Deleted Projects
        </a>

        <!-- ✅ Restore Project -->
        <a href="project_restore.php" class="menu-link <?= ($activePage == 'project_restore.php') ? 'active' : ''; ?>">
            <i class="bi bi-arrow-clockwise"></i> Restore Project
        </a>

    </div>

    <!-- System / Logout -->
    <div class="sidebar-menu mt-auto">
        <a href="../../logout.php" class="menu-link logout">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</div>
