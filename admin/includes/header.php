<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header class="admin-header">
    <div class="header-container">
        <div class="header-brand">
            <a href="../dashboard.php" class="brand-link">
                <span class="brand-logo">CBCTY</span>
                <span class="brand-text">Admin Panel</span>
            </a>
        </div>

        <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle navigation">
            <span class="menu-icon"></span>
            <span class="menu-icon"></span>
            <span class="menu-icon"></span>
        </button>

        <nav class="header-nav" id="headerNav">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="../dashboard.php" class="nav-link">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../logout.php" class="nav-link">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('mobileMenuToggle');
    const header = document.querySelector('.admin-header');
    const nav = document.getElementById('headerNav');
    
    toggleButton.addEventListener('click', function() {
        header.classList.toggle('active');
        nav.classList.toggle('active');
    });
});
</script>