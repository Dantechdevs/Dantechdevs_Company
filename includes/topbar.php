<div class="topbar">
    <button id="menu-toggle" class="btn btn-light d-lg-none">
        <i class="bi bi-list"></i>
    </button>

    <div class="d-flex align-items-center">
        <span class="me-3 fw-semibold">
            Welcome, <?= $_SESSION['username'] ?? 'User'; ?>
        </span>
        <img src="assets/img/user.png" width="38" height="38" class="rounded-circle border">
    </div>
</div>