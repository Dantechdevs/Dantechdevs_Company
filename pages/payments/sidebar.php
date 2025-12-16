<?php
/*
================================================================================
File: payments/sidebar.php
Purpose: Sidebar Navigation for Payments Module
Author: Dantechdevs
Description:
    - Payment module navigation
    - Uses same structure as Projects sidebar
    - Highlights active page
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

    <!-- Payments Module -->
    <span class="menu-title">Payments</span>

    <div class="sidebar-menu">

        <a href="payment_dashboard.php"
           class="menu-link <?= ($activePage == 'payment_dashboard.php') ? 'active' : ''; ?>">
            <i class="bi bi-cash-stack"></i> Payment Dashboard
        </a>

        <a href="invoices_list.php"
           class="menu-link <?= ($activePage == 'invoices_list.php') ? 'active' : ''; ?>">
            <i class="bi bi-receipt"></i> Invoices
        </a>

        <a href="invoice_new.php"
           class="menu-link <?= ($activePage == 'invoice_new.php') ? 'active' : ''; ?>">
            <i class="bi bi-file-earmark-plus"></i> New Invoice
        </a>

        <a href="payments_list.php"
           class="menu-link <?= ($activePage == 'payments_list.php') ? 'active' : ''; ?>">
            <i class="bi bi-credit-card"></i> All Payments
        </a>

        <a href="payment_new.php"
           class="menu-link <?= ($activePage == 'payment_new.php') ? 'active' : ''; ?>">
            <i class="bi bi-plus-circle"></i> Record Payment
        </a>

    </div>

    <!-- Quick Links -->
    <span class="menu-title">Quick Links</span>

    <div class="sidebar-menu">
        <a href="../projects/project_list.php" class="menu-link">
            <i class="bi bi-kanban-fill"></i> Projects
        </a>

        <a href="../clients/client_list.php" class="menu-link">
            <i class="bi bi-people-fill"></i> Clients
        </a>
    </div>

    <!-- System / Logout -->
    <div class="sidebar-menu mt-auto">
        <a href="../../logout.php" class="menu-link logout">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>

</div>
