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
    <title>Tentang Developer - Memorize Studio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            overflow-x: hidden;
        }
        
        .developer-section {
            background: linear-gradient(135deg, var(--pale-brown) 0%, #ffffff 100%);
            padding: 6rem 0;
            position: relative;
            overflow: hidden;
        }
        
        .developer-section::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, var(--light-brown) 0%, transparent 70%);
            z-index: 0;
        }
        
        .developer-section::after {
            content: '';
            position: absolute;
            bottom: -100px;
            left: -100px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, var(--light-brown) 0%, transparent 70%);
            z-index: 0;
        }
        
        .section-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--dark-brown);
            margin-bottom: 3rem;
            position: relative;
            padding-bottom: 15px;
            text-align: center;
            font-size: 2.5rem;
            letter-spacing: -0.5px;
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
        
        .profile-container {
            position: relative;
            z-index: 1;
            perspective: 1000px;
        }
        
        .profile-img {
            width: 280px;
            height: 280px;
            object-fit: cover;
            border: 8px solid white;
            border-radius: 12px;
            box-shadow: var(--soft-shadow);
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background-color: var(--light-brown);
            transform-style: preserve-3d;
            transform: rotateY(0deg);
            position: relative;
            z-index: 2;
        }
        
        .profile-img:hover {
            transform: rotateY(10deg) scale(1.05);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
        
        .profile-img::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(93, 64, 55, 0.1) 0%, transparent 100%);
            border-radius: 4px;
            z-index: 3;
        }
        
        .about-text {
            font-size: 1.1rem;
            margin-bottom: 1.8rem;
            color: var(--dark-gray);
            position: relative;
            z-index: 1;
        }
        
        .highlight {
            color: var(--dark-brown);
            font-weight: 600;
            position: relative;
            display: inline-block;
        }
        
        .highlight::after {
            content: '';
            position: absolute;
            bottom: 2px;
            left: 0;
            width: 100%;
            height: 6px;
            background-color: rgba(255, 171, 145, 0.4);
            z-index: -1;
            transition: height 0.2s ease;
        }
        
        .highlight:hover::after {
            height: 12px;
        }
        
        .tech-badge {
            font-size: 0.9rem;
            padding: 0.6rem 1.2rem;
            margin: 0 0.8rem 0.8rem 0;
            border-radius: 6px;
            background: linear-gradient(135deg, var(--medium-brown) 0%, var(--dark-brown) 100%);
            color: white;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            display: inline-block;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .tech-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.5s ease;
        }
        
        .tech-badge:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .tech-badge:hover::before {
            left: 100%;
        }
        
        .contact-link {
            display: inline-flex;
            align-items: center;
            margin-bottom: 1rem;
            color: var(--dark-gray);
            text-decoration: none;
            transition: all 0.3s ease;
            padding: 0.6rem 1rem;
            border-radius: 6px;
            background-color: rgba(255, 255, 255, 0.7);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            margin-right: 1rem;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .contact-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 3px;
            height: 100%;
            background-color: var(--medium-brown);
            transition: width 0.3s ease;
            z-index: -1;
        }
        
        .contact-link i {
            width: 24px;
            text-align: center;
            margin-right: 10px;
            color: var(--medium-brown);
            transition: all 0.3s ease;
        }
        
        .contact-link:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .contact-link:hover::before {
            width: 100%;
        }
        
        .contact-link:hover i {
            color: white;
        }
        
        .subtitle {
            color: var(--dark-brown);
            font-weight: 600;
            margin-bottom: 1.5rem;
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            position: relative;
            display: inline-block;
        }
        
        .subtitle::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 50%;
            height: 2px;
            background: linear-gradient(90deg, var(--medium-brown), transparent);
        }
        
        .content-box {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: var(--soft-shadow);
            position: relative;
            z-index: 1;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .content-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .floating-shape {
            position: absolute;
            opacity: 0.1;
            z-index: 0;
        }
        
        .shape-1 {
            top: 10%;
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
        
        .animated-border {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: 2px solid transparent;
            border-radius: 12px;
            animation: borderAnimation 6s linear infinite;
            pointer-events: none;
            z-index: -1;
        }
        
        @keyframes borderAnimation {
            0% { border-color: var(--light-brown); }
            25% { border-color: var(--medium-brown); }
            50% { border-color: var(--accent-color); }
            75% { border-color: var(--gold); }
            100% { border-color: var(--light-brown); }
        }
        
        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .social-link {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            background: linear-gradient(135deg, var(--medium-brown) 0%, var(--dark-brown) 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .social-link:hover {
            transform: translateY(-3px) scale(1.1);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
        
        @media (max-width: 768px) {
            .profile-img {
                width: 220px;
                height: 220px;
                margin-bottom: 2rem;
            }
            
            .developer-section {
                padding: 4rem 0;
            }
            
            .section-title {
                margin-bottom: 2rem;
                font-size: 2rem;
            }
            
            .content-box {
                padding: 1.5rem;
            }
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
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<!-- Tentang Developer Section -->
<section class="developer-section">
    <div class="floating-shape shape-1"></div>
    <div class="floating-shape shape-2"></div>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h2 class="section-title fade-in">Tentang Developer</h2>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="content-box">
                    <div class="row align-items-center">
                        <div class="col-lg-4 text-center">
                            <div class="profile-container">
                                <img src="assets/images/juseia-profile.JPG" alt="Foto Juseia" class="profile-img slide-up">
                                <div class="social-links justify-content-center mt-4 slide-up delay-1">
                                    <a href="https://instagram.com/jseylandr_" target="_blank" class="social-link">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                    <a href="https://wa.me/6281272997323" target="_blank" class="social-link">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    <a href="mailto:juseiawulan59@gmail.com" class="social-link">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                    <a href="https://github.com/juseiawulandari" target="_blank" class="social-link">
                                        <i class="fab fa-github"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-8">
                            <div class="slide-up delay-1">
                                <p class="about-text">
                                    Perkenalkan, saya <span class="highlight">Juseia Wulandari</span>, mahasiswi program studi Sistem Informasi di Universitas Sriwijaya dengan passion dalam pengembangan web dan desain antarmuka pengguna yang estetis dan fungsional.
                                </p>
                                
                                <p class="about-text">
                                    <span class="highlight">Memorize Studio</span> adalah proyek yang saya kembangkan dengan penuh dedikasi, menggabungkan konsep pembelajaran interaktif dengan pendekatan desain modern untuk menciptakan pengalaman pengguna yang menyenangkan dan efektif.
                                </p>
                            </div>
                            
                            <div class="mb-4 slide-up delay-2">
                                <h5 class="subtitle">Teknologi yang Digunakan</h5>
                                <div>
                                    <span class="tech-badge"><i class="fab fa-html5"></i> HTML5</span>
                                    <span class="tech-badge"><i class="fab fa-css3-alt"></i> CSS3</span>
                                    <span class="tech-badge"><i class="fab fa-bootstrap"></i> Bootstrap 5</span>
                                    <span class="tech-badge"><i class="fab fa-php"></i> PHP</span>
                                    <span class="tech-badge"><i class="fas fa-database"></i> MySQL</span>
                                </div>
                            </div>
                            
                            <div class="slide-up delay-3">
                                <h5 class="subtitle">Hubungi Saya</h5>
                                <div class="d-flex flex-wrap">
                                    <a href="https://instagram.com/jseylandr_" target="_blank" class="contact-link">
                                        <i class="fab fa-instagram"></i> Instagram
                                    </a>
                                    <a href="https://wa.me/6281272997323" target="_blank" class="contact-link">
                                        <i class="fab fa-whatsapp"></i> WhatsApp
                                    </a>
                                    <a href="mailto:juseiawulan59@gmail.com" class="contact-link">
                                        <i class="fas fa-envelope"></i> Email
                                    </a>
                                    <a href="https://github.com/juseiawulandari" target="_blank" class="contact-link">
                                        <i class="fab fa-github"></i> GitHub
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Additional animation triggers
    document.addEventListener('DOMContentLoaded', function() {
        const elements = document.querySelectorAll('.slide-up, .fade-in');
        
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