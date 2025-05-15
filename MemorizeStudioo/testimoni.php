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
    <title>Memorize Studio - Testimoni</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            --gold: #FFD700;
            --soft-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--cream);
            color: var(--almost-black);
            line-height: 1.8;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Hero Section */
        .testimonial-hero {
            background: 
                linear-gradient(rgba(93, 64, 55, 0.85), 
                rgba(141, 110, 99, 0.85)),
                url('assets/images/testimonial-bg.jpg') center/cover;
            color: var(--cream);
            padding: 6rem 0;
            text-align: center;
            position: relative;
            overflow: hidden;
            margin-bottom: 3rem;
        }
        
        .testimonial-hero::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, var(--light-brown) 0%, transparent 70%);
            z-index: 0;
        }
        
        .testimonial-hero::after {
            content: '';
            position: absolute;
            bottom: -100px;
            left: -100px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, var(--light-brown) 0%, transparent 70%);
            z-index: 0;
        }
        
        .testimonial-hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
        }
        
        .testimonial-hero p {
            max-width: 700px;
            margin: 0 auto;
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }
        
        /* Section Title */
        .section-title {
            font-family: 'Playfair Display', serif;
            color: var(--dark-brown);
            font-size: 2rem;
            margin-bottom: 2rem;
            position: relative;
            padding-bottom: 15px;
            text-align: center;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, var(--medium-brown), var(--accent-color));
            border-radius: 2px;
        }
        
        /* Testimonial Cards */
        .testimonial-card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--soft-shadow);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            padding: 2rem;
            background: white;
            height: 100%;
            border-top: 4px solid var(--medium-brown);
            position: relative;
            overflow: hidden;
        }
        
        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }
        
        .testimonial-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(93, 64, 55, 0.05) 0%, transparent 100%);
            z-index: 0;
        }
        
        .user-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid var(--light-brown);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }
        
        .testimonial-card:hover .user-img {
            transform: scale(1.05);
            border-color: var(--medium-brown);
        }
        
        .stars {
            color: var(--gold);
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }
        
        .testimonial-text {
            font-style: italic;
            color: var(--dark-gray);
            margin-bottom: 1.5rem;
            position: relative;
            padding: 0 1rem;
            z-index: 1;
        }
        
        .testimonial-text::before,
        .testimonial-text::after {
            content: '"';
            font-size: 2rem;
            color: var(--light-brown);
            opacity: 0.5;
            position: absolute;
            font-family: serif;
            z-index: 0;
        }
        
        .testimonial-text::before {
            top: -1rem;
            left: 0;
        }
        
        .testimonial-text::after {
            bottom: -2rem;
            right: 0;
        }
        
        .client-name {
            font-weight: 600;
            color: var(--dark-brown);
            margin-bottom: 0.25rem;
            position: relative;
            z-index: 1;
        }
        
        .client-package {
            color: var(--medium-brown);
            font-size: 0.9rem;
            position: relative;
            z-index: 1;
        }
        
        /* Form Styles */
        .add-testimonial {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            box-shadow: var(--soft-shadow);
            padding: 2.5rem;
            margin-top: 3rem;
            border-top: 4px solid var(--medium-brown);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .add-testimonial:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
        }
        
        .login-prompt {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            box-shadow: var(--soft-shadow);
            padding: 2.5rem;
            text-align: center;
            margin-top: 3rem;
            border-top: 4px solid var(--medium-brown);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .login-prompt:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
        }
        
        /* Rating Input */
        .rating-container {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .rating-input {
            display: none;
        }
        
        .rating-label {
            cursor: pointer;
            font-size: 1.5rem;
            color: var(--light-brown);
            transition: all 0.2s;
        }
        
        .rating-input:checked ~ .rating-label,
        .rating-label:hover,
        .rating-label:hover ~ .rating-label {
            color: var(--gold);
        }
        
        /* Button Styles */
        .btn-primary {
            background: linear-gradient(135deg, var(--medium-brown) 0%, var(--dark-brown) 100%);
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(93, 64, 55, 0.2);
            color: white;
        }
        
        .btn-outline-primary {
            color: var(--medium-brown);
            border-color: var(--medium-brown);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            background: linear-gradient(135deg, var(--medium-brown) 0%, var(--dark-brown) 100%);
            color: white;
            border-color: var(--medium-brown);
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(93, 64, 55, 0.2);
        }
        
        /* Form Controls */
        .form-control, .form-select {
            border: 1px solid var(--light-brown);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--medium-brown);
            box-shadow: 0 0 0 0.25rem rgba(141, 110, 99, 0.25);
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
            background-color: var(--accent-color);
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
            .testimonial-hero h1 {
                font-size: 2.4rem;
            }
        }
        
        @media (max-width: 768px) {
            .testimonial-hero {
                padding: 4rem 0;
            }
            
            .testimonial-hero h1 {
                font-size: 2rem;
            }
            
            .section-title {
                font-size: 1.75rem;
            }
            
            .testimonial-card {
                padding: 1.5rem;
            }
            
            .user-img {
                width: 80px;
                height: 80px;
            }
            
            body::before, body::after {
                display: none;
            }
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<!-- Hero Section -->
<section class="testimonial-hero">
    <div class="container">
        <h1 class="slide-up">Testimoni Klien Kami</h1>
        <p class="slide-up delay-1">Apa kata mereka tentang pengalaman berfoto di Memorize Studio</p>
    </div>
</section>

<!-- Main Content -->
<div class="container py-5">
    <div class="floating-shape shape-1"></div>
    <div class="floating-shape shape-2"></div>
    
    <div class="row">
        <!-- Testimoni 1 -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="testimonial-card slide-up">
                <div class="text-center">
                    <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Sarah Wijaya" class="user-img">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <p class="testimonial-text">"Hasil foto sangat memuaskan! Studio-nya juga nyaman, luas dan bersih. Cocok untuk foto rame-rame."</p>
                <div class="text-center">
                    <h5 class="client-name">Sarah Wijaya</h5>
                    <p class="client-package">Paket Premium</p>
                </div>
            </div>
        </div>

        <!-- Testimoni 2 -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="testimonial-card slide-up delay-1">
                <div class="text-center">
                    <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="Budi Santoso" class="user-img">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
                <p class="testimonial-text">"Proses reservasi mudah dan cepat. Hasil fotonya bagus banget, editingnya juga natural tidak berlebihan. Worth it harganya!"</p>
                <div class="text-center">
                    <h5 class="client-name">Budi Santoso</h5>
                    <p class="client-package">Paket Standard</p>
                </div>
            </div>
        </div>

        <!-- Testimoni 3 -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="testimonial-card slide-up delay-2">
                <div class="text-center">
                    <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Dewi Lestari" class="user-img">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>
                </div>
                <p class="testimonial-text">"Pengalaman foto keluarga yang menyenangkan. Anak-anak juga betah di studio karena ada banyak properti lucu. Hasil fotonya bagus semua."</p>
                <div class="text-center">
                    <h5 class="client-name">Dewi Lestari</h5>
                    <p class="client-package">Paket Premium</p>
                </div>
            </div>
        </div>

        <!-- Testimoni 4 -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="testimonial-card slide-up">
                <div class="text-center">
                    <img src="https://randomuser.me/api/portraits/men/22.jpg" alt="Andi Pratama" class="user-img">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <p class="testimonial-text">"Untuk foto bareng pacar, hasilnya sangat memukau! Kualitas fotonya profesional dan harga bersaing. Sangat recommended!"</p>
                <div class="text-center">
                    <h5 class="client-name">Andi Pratama</h5>
                    <p class="client-package">Paket Standard</p>
                </div>
            </div>
        </div>

        <!-- Testimoni 5 -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="testimonial-card slide-up delay-1">
                <div class="text-center">
                    <img src="https://randomuser.me/api/portraits/women/55.jpg" alt="Rina Amelia" class="user-img">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <p class="testimonial-text">"Pertama kali foto di sini dan hasilnya di luar ekspektasi! Bakal balik lagi untuk foto selanjutnya."</p>
                <div class="text-center">
                    <h5 class="client-name">Rina Amelia</h5>
                    <p class="client-package">Paket Basic</p>
                </div>
            </div>
        </div>

        <!-- Testimoni 6 -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="testimonial-card slide-up delay-2">
                <div class="text-center">
                    <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="David Setiawan" class="user-img">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
                <p class="testimonial-text">"Untuk foto bareng sahabat, hasilnya sangat detail dan profesional. Durasi-nya juga sesuai harga."</p>
                <div class="text-center">
                    <h5 class="client-name">David Setiawan</h5>
                    <p class="client-package">Paket Basic</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form untuk menambah testimoni -->
    <?php if ($isLoggedIn): ?>
    <div class="add-testimonial slide-up delay-3">
        <h2 class="section-title">Bagikan Pengalaman Anda</h2>
        <p class="text-center mb-4">Kami sangat menghargai setiap masukan dari pelanggan kami</p>
        
        <form>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Rating Anda</label>
                    <div class="rating-container">
                        <input type="radio" id="star5" name="rating" value="5" class="rating-input">
                        <label for="star5" class="rating-label"><i class="fas fa-star"></i></label>
                        
                        <input type="radio" id="star4" name="rating" value="4" class="rating-input">
                        <label for="star4" class="rating-label"><i class="fas fa-star"></i></label>
                        
                        <input type="radio" id="star3" name="rating" value="3" class="rating-input">
                        <label for="star3" class="rating-label"><i class="fas fa-star"></i></label>
                        
                        <input type="radio" id="star2" name="rating" value="2" class="rating-input">
                        <label for="star2" class="rating-label"><i class="fas fa-star"></i></label>
                        
                        <input type="radio" id="star1" name="rating" value="1" class="rating-input">
                        <label for="star1" class="rating-label"><i class="fas fa-star"></i></label>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <label for="package" class="form-label">Paket Yang Digunakan</label>
                    <select class="form-select" id="package" required>
                        <option value="">Pilih Paket</option>
                        <option value="Basic">Paket Basic</option>
                        <option value="Premium">Paket Standard</option>
                        <option value="Exclusive">Paket Premium</option>
                    </select>
                </div>
            </div>
            
            <div>
                <label for="testimonial" class="form-label">Testimoni Anda</label>
                <textarea class="form-control" id="testimonial" rows="4" placeholder="Bagikan pengalaman Anda menggunakan jasa kami..." required></textarea>
            </div>
            
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-5">
                    <i class="fas fa-paper-plane me-2"></i>Kirim Testimoni
                </button>
            </div>
        </form>
    </div>
    <?php else: ?>
    <div class="login-prompt slide-up delay-3">
        <h3 class="mb-3">Ingin memberikan testimoni?</h3>
        <p class="mb-4">Silakan login terlebih dahulu untuk membagikan pengalaman Anda menggunakan jasa kami.</p>
        <a href="login.php" class="btn btn-outline-primary px-4">
            <i class="fas fa-sign-in-alt me-2"></i>Login Sekarang
        </a>
    </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Rating stars interaction
    document.querySelectorAll('.rating-input').forEach(input => {
        input.addEventListener('change', function() {
            const rating = this.value;
            console.log(`Selected rating: ${rating} stars`);
        });
    });
    
    // Animation on scroll
    document.addEventListener('DOMContentLoaded', function() {
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
            el.style.opacity = 0;
            el.style.transform = 'translateY(30px)';
            observer.observe(el);
        });
    });
</script>

<?php include 'footer.php'; ?>
</body>
</html>