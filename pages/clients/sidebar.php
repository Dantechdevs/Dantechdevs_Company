<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="brand text-success">Clients</div>

    <a href="../../index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
        <i class="bi bi-speedometer2 me-2"></i> <span>Dashboard</span>
    </a>

    <a href="client_list.php" class="<?= basename($_SERVER['PHP_SELF']) == 'client_list.php' ? 'active' : '' ?>">
        <i class="bi bi-people-fill me-2"></i> <span>View Clients</span>
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

    <a href="clients_chat.php" class="<?= basename($_SERVER['PHP_SELF']) == 'clients_chat.php' ? 'active' : '' ?>">
        <i class="bi bi-chat-dots me-2"></i> <span>Chat</span>
    </a>

    <a href="deleted_clients.php"
        class="<?= basename($_SERVER['PHP_SELF']) == 'deleted_clients.php' ? 'active' : '' ?>">
        <i class="bi bi-trash me-2"></i> <span>Recycle Bin</span>
    </a>

    <a href="client_restore.php" class="<?= basename($_SERVER['PHP_SELF']) == 'client_restore.php' ? 'active' : '' ?>">
        <i class="bi bi-arrow-clockwise me-2"></i> <span>Restore Client</span>
    </a>

    <hr style="border-color: #dee2e6; margin: 20px 10px;">

    <!-- Logout -->
    <a href="../../logout.php" class="btn btn-danger mx-3 mt-auto mb-3 text-white">
        <i class="bi bi-box-arrow-right me-2"></i> Logout
    </a>
</div>

<style>
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
        overflow-y: auto;
        transition: transform 0.3s ease;
    }

    /* Sidebar brand */
    .sidebar .brand {
        font-size: 1.5rem;
        text-align: center;
        margin-bottom: 25px;
        font-weight: bold;
        letter-spacing: 1px;
    }

    /* Sidebar links consistent button-like style */
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
        /* Uniform size */
        width: calc(100% - 20px);
        box-sizing: border-box;
    }

    /* Icon styling */
    .sidebar a i {
        font-size: 1.2rem;
        transition: transform 0.3s ease;
    }

    /* Text span spacing */
    .sidebar a span {
        transition: margin-left 0.3s ease;
    }

    /* Active and hover states */
    .sidebar a:hover,
    .sidebar a.active {
        background: #198754;
        color: #fff;
        box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
    }

    .sidebar a:hover i,
    .sidebar a.active i {
        transform: translateX(5px);
        color: #fff;
    }

    .sidebar a:hover span,
    .sidebar a.active span {
        margin-left: 5px;
    }

    /* Mobile */
    @media(max-width:768px) {
        .sidebar {
            transform: translateX(-250px);
        }

        .sidebar.show {
            transform: translateX(0);
        }
    }

    /* Toggle button */
    #sidebarToggle {
        border-radius: 50%;
        padding: 10px 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease;
    }
</style>