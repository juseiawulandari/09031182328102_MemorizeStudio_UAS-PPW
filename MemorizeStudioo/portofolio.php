<?php
require_once 'auth.php';
require_once 'config/database.php';
require_once 'includes/functions.php';

$isLoggedIn = false;
$username = '';

if (function_exists('isLoggedIn')) {
    $isLoggedIn = isLoggedIn();
    if ($isLoggedIn && function_exists('getUserName')) {
        $username = getUserName();
    }
}

// Ambil semua item portofolio
$portfolio_items = getAllPortfolioItems($conn);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portofolio - Memorize Studio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
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
            --gold: #FFD700;
            --soft-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--cream);
            color: var(--almost-black);
            line-height: 1.8;
        }
        
        /* ===== HEADER SECTION ===== */
        .portfolio-hero {
            background: 
                linear-gradient(rgba(93, 64, 55, 0.85), 
                rgba(141, 110, 99, 0.85)),
                url('assets/images/portfolio-bg.jpg') center/cover;
            color: var(--cream);
            padding: 6rem 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .portfolio-hero::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, var(--light-brown) 0%, transparent 70%);
            z-index: 0;
        }
        
        .portfolio-hero::after {
            content: '';
            position: absolute;
            bottom: -100px;
            left: -100px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, var(--light-brown) 0%, transparent 70%);
            z-index: 0;
        }
        
        .portfolio-hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }
        
        .portfolio-hero p {
            max-width: 700px;
            margin: 0 auto;
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }
        
        /* ===== MAIN CONTENT ===== */
        .portfolio-section {
            padding: 6rem 0;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, var(--pale-brown) 0%, #ffffff 100%);
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
        
        .section-header {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
            z-index: 1;
        }
        
        .section-title {
            font-family: 'Playfair Display', serif;
            color: var(--dark-brown);
            font-size: 2.5rem;
            margin-bottom: 1rem;
            position: relative;
            padding-bottom: 15px;
            display: inline-block;
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
        
        /* ===== FILTER BUTTONS ===== */
        .filter-nav {
            margin-bottom: 2.5rem;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 0.5rem;
            position: relative;
            z-index: 1;
        }
        
        .filter-btn {
            background: transparent;
            border: none;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            color: var(--medium-gray);
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            border-radius: 6px;
        }
        
        .filter-btn.active, 
        .filter-btn:hover {
            color: var(--dark-brown);
            background-color: rgba(255, 255, 255, 0.7);
        }
        
        .filter-btn:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--dark-brown);
            transition: width 0.3s ease;
        }
        
        .filter-btn.active:after,
        .filter-btn:hover:after {
            width: 100%;
        }
        
        /* ===== PORTFOLIO GRID ===== */
        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            position: relative;
            z-index: 1;
        }
        
        .portfolio-card {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            height: 280px;
            box-shadow: var(--soft-shadow);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            background-color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .portfolio-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.12);
        }
        
        .portfolio-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .portfolio-card:hover .portfolio-img {
            transform: scale(1.05);
        }
        
        .card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(93, 64, 55, 0.9);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            color: var(--cream);
            text-align: center;
            padding: 1.5rem;
        }
        
        .portfolio-card:hover .card-overlay {
            opacity: 1;
        }
        
        .card-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: white;
        }
        
        .card-desc {
            font-size: 0.9rem;
            margin-bottom: 1rem;
            color: var(--light-brown);
        }
        
        .card-icon {
            color: var(--accent-color);
            font-size: 1.5rem;
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
        
        /* ===== RESPONSIVE ===== */
        @media (max-width: 992px) {
            .portfolio-hero h1 {
                font-size: 2.4rem;
            }
            
            .portfolio-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }
        
        @media (max-width: 768px) {
            .portfolio-hero {
                padding: 4rem 0;
            }
            
            .portfolio-hero h1 {
                font-size: 2rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .portfolio-card {
                height: 220px;
            }
            
            .portfolio-section {
                padding: 4rem 0;
            }
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<!-- Hero Section -->
<section class="portfolio-hero">
    <div class="container">
        <h1 class="fade-in">Portofolio Studio Kami</h1>
        <p class="fade-in delay-1">Setiap momen adalah kisah, setiap jepretan adalah seni. Kami mengabadikan keindahan dalam detail.</p>
    </div>
</section>

<!-- Portfolio Section -->
<section class="portfolio-section">
    <div class="floating-shape shape-1"></div>
    <div class="floating-shape shape-2"></div>
    
    <div class="container">
        <div class="section-header slide-up">
            <h2 class="section-title">Koleksi Terbaik</h2>
        </div>
        
        <div class="portfolio-grid">
            <?php foreach ($portfolio_items as $index => $item): ?>
            <div class="portfolio-card slide-up <?= $index < 3 ? 'delay-1' : 'delay-2' ?>" data-category="">
                <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="portfolio-img">
                <div class="card-overlay">
                    <h3 class="card-title"><?= htmlspecialchars($item['title']) ?></h3>
                    <p class="card-desc"><?= htmlspecialchars($item['description']) ?></p>
                    <i class="fas fa-camera card-icon"></i>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Portfolio Filtering
    const filterBtns = document.querySelectorAll('.filter-btn');
    const portfolioCards = document.querySelectorAll('.portfolio-card');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Update active button
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            const filter = btn.dataset.filter;
            
            // Filter cards
            portfolioCards.forEach(card => {
                if (filter === 'all' || card.dataset.category === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
    
    // Animation triggers
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