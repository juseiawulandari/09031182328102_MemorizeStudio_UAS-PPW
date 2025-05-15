<?php
require_once 'auth.php';
$isLoggedIn = false;
$username = '';

if (function_exists('isLoggedIn')) {
    $isLoggedIn = isLoggedIn();
    if ($isLoggedIn && function_exists('getUserName')) {
        $username = getUserName();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memorize Studio - Price List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --dark-brown: #5D4037;
            --medium-brown: #8D6E63;
            --light-brown: #D7CCC8;
            --pale-brown: #EFEBE9;
            --cream: #FAF9F6;
            --dark-gray: #424242;
            --medium-gray: #757575;
            --almost-black: #212121;
            --accent: #FFAB91;
            --gold: #FFD700;
            --soft-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --glow: 0 0 15px rgba(255, 171, 145, 0.5);
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--cream);
            color: var(--almost-black);
            line-height: 1.8;
            overflow-x: hidden;
        }
        
        /* Hero Section */
        .price-hero {
            background: linear-gradient(135deg, rgba(93, 64, 55, 0.85) 0%, rgba(141, 110, 99, 0.85) 100%), 
                        url('assets/images/price-bg.jpg') center/cover no-repeat;
            color: white;
            padding: 7rem 0;
            text-align: center;
            margin-bottom: 4rem;
            position: relative;
            overflow: hidden;
        }
        
        .price-hero::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(222, 184, 147, 0.2) 0%, transparent 70%);
            z-index: 0;
        }
        
        .price-hero::after {
            content: '';
            position: absolute;
            bottom: -100px;
            left: -100px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(222, 184, 147, 0.15) 0%, transparent 70%);
            z-index: 0;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
        }
        
        .price-hero h1 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 3rem;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .price-hero p {
            max-width: 700px;
            margin: 0 auto;
            font-size: 1.2rem;
            opacity: 0.9;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        
        /* Pricing Section */
        .pricing-section {
            padding: 4rem 0;
            position: relative;
        }
        
        .section-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--dark-brown);
            margin-bottom: 2rem;
            position: relative;
            padding-bottom: 0.5rem;
            text-align: center;
            font-size: 2.5rem;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, var(--medium-brown), var(--accent));
            border-radius: 2px;
        }
        
        .section-subtitle {
            color: var(--medium-gray);
            text-align: center;
            margin-bottom: 3rem;
            font-size: 1.1rem;
        }
        
        /* Price Cards */
        .price-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--soft-shadow);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin-bottom: 2rem;
            height: 100%;
            background: white;
            border: 1px solid rgba(215, 204, 200, 0.5);
            position: relative;
            backdrop-filter: blur(5px);
        }
        
        .price-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--medium-brown), var(--accent));
        }
        
        .price-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            border-color: var(--medium-brown);
        }
        
        .price-card .card-header {
            background-color: var(--dark-brown);
            color: var(--cream);
            padding: 2rem;
            text-align: center;
            border-bottom: none;
            position: relative;
            overflow: hidden;
        }
        
        .price-card .card-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent), var(--gold));
        }
        
        .price-card .card-header h3 {
            margin: 0;
            font-weight: 600;
            font-size: 1.8rem;
            position: relative;
            z-index: 1;
        }
        
        .price-card .card-body {
            padding: 2.5rem;
        }
        
        .price-card .price {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-brown);
            margin: 2rem 0;
            text-align: center;
            position: relative;
        }
        
        .price-card .price::before {
            content: '';
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, var(--medium-brown), var(--accent));
        }
        
        .price-card .price::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, var(--medium-brown), var(--accent));
        }
        
        .price-card ul {
            margin-bottom: 2.5rem;
            padding-left: 0;
        }
        
        .price-card ul li {
            padding: 1rem 0;
            border-bottom: 1px dashed var(--light-brown);
            list-style-type: none;
            display: flex;
            align-items: center;
            color: var(--dark-gray);
            transition: all 0.3s ease;
        }
        
        .price-card ul li:hover {
            color: var(--almost-black);
            transform: translateX(5px);
        }
        
        .price-card ul li:last-child {
            border-bottom: none;
        }
        
        .price-card ul li i {
            margin-right: 1rem;
            width: 24px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        .price-card ul li .fa-check {
            color: var(--medium-brown);
        }
        
        .price-card ul li .fa-times {
            color: var(--medium-gray);
        }
        
        /* Carousel Styles */
        .carousel-inner {
            border-radius: 16px 16px 0 0;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .carousel-item img {
            height: 250px;
            object-fit: cover;
            width: 100%;
            filter: brightness(0.9);
            transition: filter 0.3s ease;
        }
        
        .carousel-item:hover img {
            filter: brightness(1);
        }
        
        .carousel-control-prev, 
        .carousel-control-next {
            background-color: rgba(93, 64, 55, 0.5);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.9;
            transition: all 0.3s ease;
        }
        
        .carousel-control-prev:hover, 
        .carousel-control-next:hover {
            background-color: rgba(93, 64, 55, 0.8);
            transform: translateY(-50%) scale(1.1);
        }
        
        /* Button Styles */
        .btn-primary {
            background: linear-gradient(135deg, var(--medium-brown) 0%, var(--dark-brown) 100%);
            border: none;
            padding: 1rem 2rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(93, 64, 55, 0.3);
            width: 100%;
            position: relative;
            overflow: hidden;
            border-radius: 8px;
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.5s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(93, 64, 55, 0.4);
        }
        
        .btn-primary:hover::before {
            left: 100%;
        }
        
        /* Floating Shapes */
        .floating-shape {
            position: absolute;
            opacity: 0.1;
            z-index: 0;
        }
        
        .shape-1 {
            top: 20%;
            left: 5%;
            width: 100px;
            height: 100px;
            background-color: var(--medium-brown);
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            animation: float 8s ease-in-out infinite;
        }
        
        .shape-2 {
            bottom: 15%;
            right: 10%;
            width: 150px;
            height: 150px;
            background-color: var(--accent);
            border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
            animation: float 10s ease-in-out infinite;
            animation-delay: 1s;
        }
        
        @keyframes float {
            0% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
            100% { transform: translateY(0) rotate(0deg); }
        }
        
        /* Animations */
        .fade-in {
            animation: fadeIn 1s ease-out forwards;
        }
        
        .slide-up {
            animation: slideUp 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(30px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .delay-1 {
            animation-delay: 0.2s;
        }
        
        .delay-2 {
            animation-delay: 0.4s;
        }
        
        .delay-3 {
            animation-delay: 0.6s;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .price-hero h1 {
                font-size: 2.5rem;
            }
            
            .price-card .card-body {
                padding: 2rem;
            }
        }
        
        @media (max-width: 768px) {
            .price-hero {
                padding: 5rem 0;
            }
            
            .price-hero h1 {
                font-size: 2.2rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .carousel-item img {
                height: 220px;
            }
        }
        
        @media (max-width: 576px) {
            .price-hero {
                padding: 4rem 0;
            }
            
            .price-hero h1 {
                font-size: 2rem;
            }
            
            .price-card .card-body {
                padding: 1.5rem;
            }
            
            .price-card .price {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<!-- Hero Section -->
<section class="price-hero fade-in">
    <div class="floating-shape shape-1"></div>
    <div class="floating-shape shape-2"></div>
    
    <div class="container hero-content">
        <h1 class="slide-up">Paket Foto Studio</h1>
        <p class="slide-up delay-1">Temukan paket yang sempurna untuk kebutuhan fotografi Anda</p>
    </div>
</section>

<!-- Pricing Section -->
<section class="pricing-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title slide-up">Daftar Harga</h2>
            <p class="section-subtitle slide-up delay-1">Pilih paket yang sesuai dengan kebutuhan Anda</p>
        </div>
        
        <div class="row g-4">
            <!-- Paket Basic -->
            <div class="col-lg-4 col-md-6">
                <div class="price-card slide-up">
                    <div id="carouselBasic" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="assets/images/basic1.jpg" class="d-block w-100" alt="Basic Package 1">
                            </div>
                            <div class="carousel-item">
                                <img src="assets/images/basic2.jpg" class="d-block w-100" alt="Basic Package 2">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselBasic" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselBasic" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    <div class="card-header">
                        <h3>Paket Basic</h3>
                    </div>
                    <div class="card-body">
                        <div class="price">Rp 90.000</div>
                        <ul>
                            <li><i class="fas fa-check"></i> Maksimal 2 Orang</li>
                            <li><i class="fas fa-check"></i> 1 Jam Sesi Foto</li>
                            <li><i class="fas fa-check"></i> Free Soft File</li>
                            <li><i class="fas fa-check"></i> Free 2 Cetak Foto</li>
                        </ul>
                        <?php if ($isLoggedIn): ?>
                            <a href="reservasi.php" class="btn btn-primary">Reservasi Sekarang</a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-primary">Login untuk Reservasi</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Paket Standard -->
            <div class="col-lg-4 col-md-6">
                <div class="price-card slide-up delay-1">
                    <div id="carouselStandard" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="assets/images/standard1.jpg" class="d-block w-100" alt="Standard Package 1">
                            </div>
                            <div class="carousel-item">
                                <img src="assets/images/standard2.jpg" class="d-block w-100" alt="Standard Package 2">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselStandard" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselStandard" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    <div class="card-header">
                        <h3>Paket Standard</h3>
                    </div>
                    <div class="card-body">
                        <div class="price">Rp 210.000</div>
                        <ul>
                            <li><i class="fas fa-check"></i> Maksimal 4 Orang</li>
                            <li><i class="fas fa-check"></i> 2 Jam Sesi Foto</li>
                            <li><i class="fas fa-check"></i> Free Soft File</li>
                            <li><i class="fas fa-check"></i> Free 4 Cetak Foto</li>
                        </ul>
                        <?php if ($isLoggedIn): ?>
                            <a href="reservasi.php" class="btn btn-primary">Reservasi Sekarang</a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-primary">Login untuk Reservasi</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Paket Premium -->
            <div class="col-lg-4 col-md-6">
                <div class="price-card slide-up delay-2">
                    <div id="carouselPremium" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="assets/images/premium1.jpg" class="d-block w-100" alt="Premium Package 1">
                            </div>
                            <div class="carousel-item">
                                <img src="assets/images/premium2.jpg" class="d-block w-100" alt="Premium Package 2">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselPremium" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselPremium" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    <div class="card-header">
                        <h3>Paket Premium</h3>
                    </div>
                    <div class="card-body">
                        <div class="price">Rp 485.000</div>
                        <ul>
                            <li><i class="fas fa-check"></i> Maksimal 20 Orang</li>
                            <li><i class="fas fa-check"></i> 4 Jam Sesi Foto</li>
                            <li><i class="fas fa-check"></i> Free Soft File</li>
                            <li><i class="fas fa-check"></i> Free 10 Cetak Foto</li>
                        </ul>
                        <?php if ($isLoggedIn): ?>
                            <a href="reservasi.php" class="btn btn-primary">Reservasi Sekarang</a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-primary">Login untuk Reservasi</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Enable carousel auto-cycling
    document.addEventListener('DOMContentLoaded', function() {
        var carousels = document.querySelectorAll('.carousel');
        carousels.forEach(function(carousel) {
            new bootstrap.Carousel(carousel, {
                interval: 3000,
                wrap: true,
                pause: false
            });
        });
        
        // Animation on scroll
        const elements = document.querySelectorAll('.slide-up');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = 1;
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });
        
        elements.forEach(el => {
            observer.observe(el);
        });
    });
</script>
</body>
</html>