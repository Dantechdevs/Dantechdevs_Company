<?php
// Set timezone
date_default_timezone_set('Africa/Nairobi');

// Determine greeting
$hour = (int) date('H');
if ($hour >= 5 && $hour < 12) {
    $greeting = "Good morning";
} elseif ($hour >= 12 && $hour < 17) {
    $greeting = "Good afternoon";
} elseif ($hour >= 17 && $hour < 21) {
    $greeting = "Good evening";
} else {
    $greeting = "Good night";
}
?>

<!-- Topbar -->
<div class="topbar-clean d-flex justify-content-between align-items-center p-2 shadow-sm">

    <!-- LEFT: Logo + Company Name -->
    <div class="d-flex align-items-center gap-2">
        <img src="assets/img/logo.jpg" class="topbar-logo" alt="Logo" style="height:40px;">
        <span class="fw-bold fs-5">Dantechdevs IT Company</span>
    </div>

    <!-- RIGHT: Greeting + Profile -->
    <div class="d-flex align-items-center gap-3">

        <!-- Greeting Pill -->
        <div class="greeting-pill px-2 py-1 rounded bg-light border d-flex align-items-center gap-1">
            <span class="dot bg-success rounded-circle" style="width:8px; height:8px; display:inline-block;"></span>
            <?= $greeting ?>
        </div>

        <!-- Profile Dropdown -->
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-primary dropdown-toggle d-flex align-items-center gap-2" type="button"
                id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <span><?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></span>
                <div class="avatar-circle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                    style="width:32px; height:32px; font-weight:bold;">
                    <?= strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)) ?>
                </div>
            </button>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">

                <!-- Profile -->
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2" href="profile/profile.php">
                        <i class="bi bi-person"></i> Profile
                    </a>
                </li>

                <!-- Settings -->
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2" href="profile/settings.php">
                        <i class="bi bi-gear"></i> Settings
                    </a>
                </li>

                <!-- Dark Mode Toggle -->
                <li>
                    <div class="dropdown-item d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-moon me-1"></i> Dark Mode</span>
                        <input type="checkbox" class="form-check-input m-0" id="darkModeSwitch">
                    </div>
                </li>

                <li>
                    <hr class="dropdown-divider">
                </li>

                <!-- Logout -->
                <li>
                    <a class="dropdown-item text-danger d-flex align-items-center gap-2" href="auth/logout.php">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </li>

            </ul>
        </div>

    </div>
</div>

<!-- Bootstrap 5 JS + Popper -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

<script>
    // Dark Mode Toggle
    const darkModeSwitch = document.getElementById('darkModeSwitch');
    darkModeSwitch.addEventListener('change', () => {
        document.body.classList.toggle('dark-mode');
    });
</script>

<style>
    /* Optional: greeting pill styling */
    .greeting-pill .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .avatar-circle {
        font-size: 0.85rem;
    }
</style>