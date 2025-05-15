<?php
// Pastikan variabel ini sudah didefinisikan
// $isLoggedIn = status login user (true/false)
// $username = nama user yang login
?>

<!-- Warm Earthy Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background-color: #282728; border-bottom: 1px solid #7c543b;">
    <div class="container py-1">
        <!-- Brand with custom logo -->
        <a class="navbar-brand d-flex align-items-center" href="index.php" style="font-size: 0.95rem; letter-spacing: 1px; transition: all 0.3s ease;">
            
            <span style="color: #ede1d5; font-weight: 500; letter-spacing: 0.5px;">MemorizeStudio</span>
        </a>
        
        <!-- Toggle Button -->
        <button class="navbar-toggler border-0 py-1 px-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <div class="hamburger">
                <span class="hamburger-line" style="background-color: #deb893;"></span>
                <span class="hamburger-line" style="background-color: #deb893;"></span>
                <span class="hamburger-line" style="background-color: #deb893;"></span>
            </div>
        </button>
        
        <!-- Main Menu -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto" style="font-size: 0.85rem;">
                <li class="nav-item">
                    <a class="nav-link py-1 px-2 position-relative <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>" href="index.php" style="color: #e6ccb3;">
                        <i class="fas fa-home me-1"></i> Home
                        <span class="nav-underline"></span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link py-1 px-2 position-relative <?= basename($_SERVER['PHP_SELF']) == 'price-list.php' ? 'active' : '' ?>" href="price-list.php" style="color: #e6ccb3;">
                        <i class="fas fa-tags me-1"></i> Pricing
                        <span class="nav-underline"></span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link py-1 px-2 position-relative <?= basename($_SERVER['PHP_SELF']) == 'portofolio.php' ? 'active' : '' ?>" href="portofolio.php" style="color: #e6ccb3;">
                        <i class="fas fa-images me-1"></i> Portfolio
                        <span class="nav-underline"></span>
                    </a>
                </li>
                
                <!-- Services Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle py-1 px-2 position-relative <?= (basename($_SERVER['PHP_SELF']) == 'reservasi.php' || basename($_SERVER['PHP_SELF']) == 'update-reservasi.php' || basename($_SERVER['PHP_SELF']) == 'delete-reservasi.php') ? 'active' : '' ?>" href="#" id="layananDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #e6ccb3;">
                        <i class="fas fa-concierge-bell me-1"></i> Services
                        <span class="nav-underline"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-animate" aria-labelledby="layananDropdown" style="font-size: 0.85rem; background-color: #282728; border: 1px solid #7c543b;">
                        <?php if ($isLoggedIn): ?>
                            <li><a class="dropdown-item py-2 px-3 d-flex align-items-center" href="reservasi.php" style="color: #e6ccb3; transition: all 0.2s;"><i class="fas fa-calendar-plus me-2" style="width: 20px; text-align: center; color: #9c6644;"></i>New Booking</a></li>
                            <li><hr class="dropdown-divider my-1" style="border-color: #7c543b;"></li>
                            <li><a class="dropdown-item py-2 px-3 d-flex align-items-center" href="update-reservasi.php" style="color: #e6ccb3;"><i class="fas fa-edit me-2" style="width: 20px; text-align: center; color: #9c6644;"></i>Manage Booking</a></li>
                            <li><a class="dropdown-item py-2 px-3 d-flex align-items-center" href="delete-reservasi.php" style="color: #e6ccb3;"><i class="fas fa-trash-alt me-2" style="width: 20px; text-align: center; color: #9c6644;"></i>Cancel Booking</a></li>
                        <?php else: ?>
                            <li><a class="dropdown-item py-2 px-3 d-flex align-items-center" href="login.php" style="color: #e6ccb3;"><i class="fas fa-sign-in-alt me-2" style="width: 20px; text-align: center; color: #9c6644;"></i>Login to Book</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link py-1 px-2 position-relative <?= basename($_SERVER['PHP_SELF']) == 'testimoni.php' ? 'active' : '' ?>" href="testimoni.php" style="color: #e6ccb3;">
                        <i class="fas fa-comment-dots me-1"></i> Testimonials
                        <span class="nav-underline"></span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link py-1 px-2 position-relative <?= basename($_SERVER['PHP_SELF']) == 'tentang-dev.php' ? 'active' : '' ?>" href="tentang-dev.php" style="color: #e6ccb3;">
                        <i class="fas fa-code me-1"></i> Developer
                        <span class="nav-underline"></span>
                    </a>
                </li>
            </ul>
            
            <!-- User Menu -->
            <ul class="navbar-nav ms-auto" style="font-size: 0.85rem;">
                <?php if ($isLoggedIn): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center py-1 px-2" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #ede1d5;">
                            <div class="avatar-circle me-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 0.8rem; background: linear-gradient(135deg, #9c6644, #7c543b);">
                                <?= strtoupper(substr(htmlspecialchars($username), 0, 1)) ?>
                            </div>
                            <span class="username-text"><?= htmlspecialchars($username) ?></span>
                            <i class="fas fa-chevron-down ms-1" style="font-size: 0.7rem; color: #deb893;"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-animate" aria-labelledby="userDropdown" style="font-size: 0.85rem; background-color: #282728; border: 1px solid #7c543b;">
                            <li><hr class="dropdown-divider my-1" style="border-color: #7c543b;"></li>
                            <li><a class="dropdown-item py-2 px-3 d-flex align-items-center" href="logout.php" style="color: #deb893;"><i class="fas fa-sign-out-alt me-2" style="width: 20px; text-align: center; color: #9c6644;"></i>Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm py-1 px-3 me-1 rounded-pill btn-hover" href="register.php" style="font-size: 0.8rem; border-color: #9c6644; color: #deb893;">
                            <i class="fas fa-user-plus me-1"></i> Register
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm py-1 px-3 rounded-pill btn-hover" href="login.php" style="font-size: 0.8rem; background: linear-gradient(135deg, #9c6644, #7c543b); border: none; color: #ede1d5;">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Earthy Color Palette CSS -->
<style>
    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }
    
    /* Logo animation */
    .navbar-brand:hover .logo-square:nth-child(1),
    .navbar-brand:hover .logo-square:nth-child(4) {
        background-color: #deb893;
        transition: all 0.3s ease;
    }
    
    .navbar-brand:hover .logo-square:nth-child(2),
    .navbar-brand:hover .logo-square:nth-child(3) {
        background-color: #9c6644;
        transition: all 0.3s ease;
    }
    
    /* Hamburger menu animation */
    .hamburger {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 14px;
        width: 18px;
    }
    
    .hamburger-line {
        height: 2px;
        width: 100%;
        transition: all 0.3s ease;
    }
    
    .navbar-toggler[aria-expanded="true"] .hamburger-line:nth-child(1) {
        transform: translateY(6px) rotate(45deg);
        background-color: #9c6644;
    }
    
    .navbar-toggler[aria-expanded="true"] .hamburger-line:nth-child(2) {
        opacity: 0;
    }
    
    .navbar-toggler[aria-expanded="true"] .hamburger-line:nth-child(3) {
        transform: translateY(-6px) rotate(-45deg);
        background-color: #9c6644;
    }
    
    /* Animated underline for nav items */
    .nav-underline {
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 2px;
        background: linear-gradient(90deg, #9c6644, #deb893);
        transition: width 0.3s ease;
    }
    
    .nav-link:hover .nav-underline,
    .nav-link.active .nav-underline {
        width: calc(100% - 16px);
    }
    
    /* Hover effects */
    .nav-link {
        transition: all 0.3s ease;
        position: relative;
        margin: 0 4px;
        border-radius: 4px;
    }
    
    .navbar-nav {
        gap: 0.2rem;
    }
    
    .nav-link {
        padding: 0.4rem 0.8rem !important;
    }
    
    .nav-link:hover {
        color: #ede1d5 !important;
        background-color: rgba(156, 102, 68, 0.1);
    }
    
    .nav-link.active {
        color: #ede1d5 !important;
        font-weight: 500;
        background-color: rgba(156, 102, 68, 0.2);
    }
    
    /* Avatar circle */
    .avatar-circle {
        border-radius: 50%;
        color: #ede1d5;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    /* Dropdown animations */
    .dropdown-animate {
        animation: fadeIn 0.2s ease-in-out;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(5px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Dropdown styling */
    .dropdown-menu {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        border-radius: 6px;
        padding: 0.3rem 0;
        min-width: 200px;
    }
    
    .dropdown-item {
        border-radius: 4px;
        margin: 0 3px;
        transition: all 0.2s;
    }
    
    .dropdown-item:hover {
        background-color: rgba(156, 102, 68, 0.2);
        color: #ede1d5 !important;
        padding-left: 15px !important;
    }
    
    /* Button hover effects */
    .btn-hover {
        transition: all 0.3s ease;
    }
    
    .btn-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #7c543b, #9c6644) !important;
    }
    
    .btn-outline-light:hover {
        background-color: rgba(156, 102, 68, 0.1);
        color: #ede1d5 !important;
    }
    
    /* Mobile responsiveness */
    @media (max-width: 991.98px) {
        .navbar-collapse {
            background-color: #282728;
            padding: 0.8rem;
            border-radius: 8px;
            margin-top: 0.8rem;
            border: 1px solid #7c543b;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .nav-item {
            margin: 0.2rem 0;
        }
        
        .dropdown-menu {
            background-color: #282728 !important;
            margin-left: 1rem;
            border: 1px solid #7c543b;
        }
        
        .username-text {
            display: inline-block;
        }
        
        .navbar-nav {
            gap: 0.5rem;
        }
    }
    
    /* Smooth scroll behavior */
    html {
        scroll-behavior: smooth;
    }
</style>