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
    <title>Memorize Studio - Premium Photography Experience</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            --accent-color: #FFAB91;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--cream);
            color: var(--almost-black);
            line-height: 1.6;
        }
        
        /* Header Styles */
        .page-header {
            text-align: center;
            margin-bottom: 2.5rem;
            position: relative;
            padding-bottom: 1rem;
        }
        
        .page-header h2 {
            color: var(--dark-brown);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .page-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 25%;
            width: 50%;
            height: 3px;
            background: linear-gradient(90deg, var(--light-brown), var(--dark-brown), var(--light-brown));
            border-radius: 3px;
        }
        
        /* Carousel Styles */
        .carousel-item {
            position: relative;
            height: 80vh;
        }
        
        .carousel-item img {
            object-fit: cover;
            height: 100%;
            width: 100%;
            filter: brightness(0.7);
        }
        
        .carousel-caption {
            bottom: 30%;
            z-index: 1;
        }
        
        .carousel-caption h3 {
            font-weight: 700;
            font-size: 3rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
        }
        
        /* Button Styles */
        .btn-primary {
            background-color: var(--medium-brown);
            border-color: var(--medium-brown);
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: var(--dark-brown);
            border-color: var(--dark-brown);
            transform: translateY(-2px);
        }
        
        .btn-outline-primary {
            border: 2px solid var(--medium-brown);
            color: var(--medium-brown);
            background: transparent;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            background-color: var(--medium-brown);
            color: white;
        }
        
        /* Card Styles */
        .card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }
        
        .card-header {
            background-color: var(--dark-brown);
            color: var(--cream);
            font-weight: 600;
            padding: 1.25rem 1.5rem;
            border-bottom: none;
        }
        
        .card-body {
            background-color: var(--pale-brown);
            padding: 1.5rem;
        }
        
        /* Feature Card Styles */
        .feature-card {
            border-top: 4px solid var(--medium-brown);
        }
        
        .feature-card i {
            color: var(--medium-brown);
            background: rgba(141, 110, 99, 0.1);
            width: 70px;
            height: 70px;
            line-height: 70px;
            border-radius: 50%;
            margin-bottom: 1rem;
            font-size: 2rem;
        }
        
        /* Pricing Card Styles */
        .pricing-card .card-header {
            background-color: var(--dark-brown);
            color: white;
            padding: 1.5rem;
            text-align: center;
            font-size: 1.5rem;
        }
        
        .price {
            font-weight: 700;
            color: var(--dark-brown);
        }
        
        /* Testimonial Styles */
        .testimonial-card {
            border-top: 4px solid var(--medium-brown);
        }
        
        .testimonial-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }
        
        /* Section Styles */
        .section-title {
            position: relative;
            margin-bottom: 2.5rem;
            text-align: center;
        }
        
        .section-title:after {
            content: "";
            display: block;
            width: 100px;
            height: 4px;
            background: var(--medium-brown);
            margin: 1rem auto 0;
        }
        
        /* How It Works Styles */
        .step-number {
            background: var(--medium-brown);
            color: white;
            width: 50px;
            height: 50px;
            line-height: 50px;
            border-radius: 50%;
            font-weight: 700;
            margin: 0 auto 1rem;
        }
        
        /* Portfolio Styles */
        .portfolio-card {
            height: 250px;
            overflow: hidden;
            border-radius: 8px;
            position: relative;
        }
        
        .portfolio-card img {
            height: 100%;
            width: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .portfolio-card:hover img {
            transform: scale(1.1);
        }
        
        /* Background Styles */
        .bg-light-custom {
            background-color: var(--light-brown);
        }
        
        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .carousel-item {
                height: 60vh;
            }
            
            .carousel-caption h3 {
                font-size: 2.5rem;
            }
        }
        
        @media (max-width: 768px) {
            .carousel-item {
                height: 50vh;
            }
            
            .carousel-caption h3 {
                font-size: 2rem;
            }
            
            .carousel-caption {
                bottom: 20%;
            }
        }
        
        @media (max-width: 576px) {
            .carousel-item {
                height: 40vh;
            }
            
            .carousel-caption h3 {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<!-- Hero Carousel -->
<div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2"></button>
    </div>

    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="assets/images/slide1.png" alt="Professional photography studio">
            <div class="carousel-caption">
                <h3>CAPTURE TIMELESS MOMENTS</h3>
                <p class="mb-4">Professional studio photography for your most precious memories</p>
                <a href="#packages" class="btn btn-primary">VIEW PACKAGES</a>
            </div>
        </div>
        <div class="carousel-item">
            <img src="assets/images/slide2.png" alt="Example photo session">
            <div class="carousel-caption">
                <h3>STUNNING PORTRAITS</h3>
                <p class="mb-4">Self-shoot. Self-love. Your style, your story</p>
                <a href="#packages" class="btn btn-primary">BOOK NOW</a>
            </div>
        </div>
        <div class="carousel-item">
            <img src="assets/images/slide3.png" alt="Studio interior">
            <div class="carousel-caption">
                <h3>PERFECT SETTINGS</h3>
                <p class="mb-4">Multiple backdrops and professional lighting for every occasion</p>
                <a href="#gallery" class="btn btn-primary">VIEW GALLERY</a>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<!-- Main Content -->
<div class="container mt-5">
    
    <!-- Welcome Section -->
    <section class="text-center mb-5 py-5">
        <h1 class="display-5 mb-4 fw-bold" style="color: var(--dark-brown);">MEMORIZE STUDIO</h1>
        <p class="lead mb-4" style="max-width: 800px; margin: 0 auto;">
            Where moments become art. With over a decade of experience, we specialize in creating stunning visual narratives that last a lifetime.
        </p>
        <div class="mt-5">
            <a href="#how-it-works" class="btn btn-primary me-3">HOW IT WORKS</a>
            <a href="#testimonials" class="btn btn-outline-primary">CLIENT STORIES</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="mb-5 py-5">
        <h2 class="section-title">Why Choose Us?</h2>
        <div class="row g-4">
            <div class="col-md-4">
    <div class="card feature-card text-center p-4">
        <i class="fas fa-camera-retro mb-3"></i>
        <h4 class="mb-3">Freedom to Pose</h4>
        <p>Take control of the camera and express yourself freely — it’s your space, your moment, your rules.</p>
    </div>
</div>
<div class="col-md-4">
    <div class="card feature-card text-center p-4">
        <i class="fas fa-lightbulb mb-3"></i>
        <h4 class="mb-3">Studio-Grade Equipment</h4>
        <p>Enjoy professional lighting and high-quality cameras that make every self-shot look stunning.</p>
    </div>
</div>
<div class="col-md-4">
    <div class="card feature-card text-center p-4">
        <i class="fas fa-magic mb-3"></i>
        <h4 class="mb-3">Easy Enhancements</h4>
        <p>Edit your photos with built-in filters or leave them as they are — naturally beautiful and uniquely yours.</p>
    </div>
</div>
        </div>
    </section>

    <!-- Popular Packages -->
    <section id="packages" class="mb-5 py-5">
        <h2 class="section-title">Our Packages</h2>
        <div class="row g-4">
            <!-- Basic Package -->
            <div class="col-md-4">
                <div class="card pricing-card">
                    <div class="card-header">BASIC</div>
                    <div class="card-body text-center">
                        <div class="price mb-4">
                            <span class="h3">Rp</span>
                            <span class="display-5 fw-bold">90</span>
                            <span class="h3">.000</span>
                        </div>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Up to 2 People</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>1 Hour Session</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Digital Files</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>2 Printed Photos</li>
                        </ul>
                        <?php if ($isLoggedIn): ?>
                            <a href="reservasi.php" class="btn btn-primary w-100">BOOK NOW</a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-primary w-100">LOGIN TO BOOK</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Standard Package -->
            <div class="col-md-4">
                <div class="card pricing-card">
                    <div class="card-header">STANDARD</div>
                    <div class="card-body text-center">
                        <div class="price mb-4">
                            <span class="h3">Rp</span>
                            <span class="display-5 fw-bold">210</span>
                            <span class="h3">.000</span>
                        </div>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Up to 4 People</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>2 Hour Session</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Digital Files</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>4 Printed Photos</li>
                        </ul>
                        <?php if ($isLoggedIn): ?>
                            <a href="reservasi.php" class="btn btn-primary w-100">BOOK NOW</a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-primary w-100">LOGIN TO BOOK</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Premium Package -->
            <div class="col-md-4">
                <div class="card pricing-card">
                    <div class="card-header">PREMIUM</div>
                    <div class="card-body text-center">
                        <div class="price mb-4">
                            <span class="h3">Rp</span>
                            <span class="display-5 fw-bold">485</span>
                            <span class="h3">.000</span>
                        </div>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Up to 20 People</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>4 Hour Session</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Digital Files</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>10 Printed Photos</li>
                        </ul>
                        <?php if ($isLoggedIn): ?>
                            <a href="reservasi.php" class="btn btn-primary w-100">BOOK NOW</a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-primary w-100">LOGIN TO BOOK</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-5">
            <a href="price-list.php" class="btn btn-outline-primary">VIEW ALL PACKAGES</a>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials" class="mb-5 py-5 bg-light-custom rounded">
        <h2 class="section-title">Client Testimonials</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card testiomonial-card">
                    <div class="card-body text-center">
                        <img src="https://randomuser.me/api/portraits/women/32.jpg" class="rounded-circle testimonial-img mb-3" alt="Sarah">
                        <div class="text-warning mb-3">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="mb-3 fst-italic">
                            "Hasil foto sangat memuaskan! Studio-nya juga nyaman, luas dan bersih. Cocok untuk foto rame-rame."
                        </p>
                        <h5>Sarah Wijaya</h5>
                        <p class="text-muted small">Premium Package</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card testiomonial-card">
                    <div class="card-body text-center">
                        <img src="https://randomuser.me/api/portraits/men/45.jpg" class="rounded-circle testimonial-img mb-3" alt="Budi">
                        <div class="text-warning mb-3">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        </div>
                        <p class="mb-3 fst-italic">
                            "Proses reservasi mudah dan cepat. Hasil fotonya bagus banget, editingnya juga natural tidak berlebihan. Worth it harganya!"
                        </p>
                        <h5>Budi Santoso</h5>
                        <p class="text-muted small">Standard Package</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card testiomonial-card">
                    <div class="card-body text-center">
                        <img src="https://randomuser.me/api/portraits/women/68.jpg" class="rounded-circle testimonial-img mb-3" alt="Dewi">
                        <div class="text-warning mb-3">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="mb-3 fst-italic">
                            "Pengalaman foto keluarga yang menyenangkan. Anak-anak juga betah di studio karena ada banyak properti lucu. Hasil fotonya bagus semua."
                        </p>
                        <h5>Dewi Lestari</h5>
                        <p class="text-muted small">Premium Package</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section id="how-it-works" class="mb-5 py-5">
        <h2 class="section-title">How It Works</h2>
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 bg-transparent">
                    <div class="card-body">
                        <div class="step-number mb-3">1</div>
                        <h4 class="mb-3">Choose Package</h4>
                        <p>Select from our carefully crafted photography packages designed for every need and budget.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 bg-transparent">
                    <div class="card-body">
                        <div class="step-number mb-3">2</div>
                        <h4 class="mb-3">Book Session</h4>
                        <p>Schedule your preferred date and time through our easy online booking system.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 bg-transparent">
                    <div class="card-body">
                        <div class="step-number mb-3">3</div>
                        <h4 class="mb-3">Enjoy & Receive</h4>
                        <p>Have a wonderful photoshoot experience and receive your professionally edited photos.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <?php if ($isLoggedIn): ?>
                <a href="reservasi.php" class="btn btn-primary px-5">BOOK YOUR SESSION</a>
            <?php else: ?>
                <a href="register.php" class="btn btn-primary px-5">REGISTER NOW</a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Portfolio Preview -->
    <section id="gallery" class="mb-5 py-5">
        <h2 class="section-title">Our Portfolio</h2>
        <div class="row g-4">
            <div class="col-md-4 col-6">
                <div class="card portfolio-card">
                    <img src="assets/images/porto1.jpg" class="card-img-top" alt="Wedding Photography">
                </div>
            </div>
            <div class="col-md-4 col-6">
                <div class="card portfolio-card">
                    <img src="assets/images/porto2.jpg" class="card-img-top" alt="Portrait Photography">
                </div>
            </div>
            <div class="col-md-4 col-6">
                <div class="card portfolio-card">
                    <img src="assets/images/porto3.jpg" class="card-img-top" alt="Family Photography">
                </div>
            </div>
            <div class="col-md-4 col-6">
                <div class="card portfolio-card">
                    <img src="assets/images/porto4.jpg" class="card-img-top" alt="Event Photography">
                </div>
            </div>
            <div class="col-md-4 col-6">
                <div class="card portfolio-card">
                    <img src="assets/images/porto5.jpg" class="card-img-top" alt="Wedding Photography">
                </div>
            </div>
            <div class="col-md-4 col-6">
                <div class="card portfolio-card">
                    <img src="assets/images/porto6.jpg" class="card-img-top" alt="Portrait Photography">
                </div>
            </div>
        </div>
        <div class="text-center mt-5">
            <a href="portofolio.php" class="btn btn-outline-primary">VIEW FULL GALLERY</a>
        </div>
    </section>
    
    <!-- Call to Action -->
    <section class="mb-5 py-5 bg-light-custom rounded text-center">
        <h2 class="mb-4">READY TO CREATE STUNNING MEMORIES?</h2>
        <p class="lead mb-5">Contact us today to book your session or ask any questions</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="https://wa.me/6281234567890" class="btn btn-primary">
                <i class="fas fa-envelope me-2"></i>CONTACT US
            </a>
            <a href="tel:+628123456789" class="btn btn-outline-primary">
                <i class="fas fa-phone me-2"></i>CALL NOW
            </a>
        </div>
    </section>
</div>

<?php include 'footer.php'; ?>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
    
    // Initialize carousels
    var carousels = document.querySelectorAll('.carousel');
    carousels.forEach(function(carousel) {
        new bootstrap.Carousel(carousel, {
            interval: 5000,
            wrap: true,
            pause: false
        });
    });
</script>
</body>
</html>