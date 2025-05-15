<?php
// footer.php - Harmonized with Memorize Studio design system
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        /* Earthy Neutral Footer - Harmonized with Memorize Studio */
        :root {
            --dark-brown: #5D4037;
            --medium-brown: #8D6E63;
            --light-brown: #D7CCC8;
            --pale-brown: #EFEBE9;
            --cream: #FAF9F6;
            --dark-gray: #424242;
            --medium-gray: #757575;
            --almost-black: #212121;
        }
        
        .ms-footer {
            background-color: var(--pale-brown);
            color: var(--almost-black);
            padding: 4rem 0 1.5rem;
            font-family: 'Poppins', sans-serif;
            border-top: 1px solid var(--light-brown);
        }

        .ms-footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .ms-footer-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .ms-footer-col {
            margin-bottom: 1.5rem;
        }

        .ms-footer-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--dark-brown);
            position: relative;
            padding-bottom: 0.5rem;
        }

        .ms-footer-title:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 2.5rem;
            height: 2px;
            background: var(--medium-brown);
        }

        .ms-footer-logo h2 {
            font-size: 1.5rem;
            color: var(--medium-brown);
            margin: 0 0 1rem 0;
            font-weight: 700;
        }

        .ms-footer-about {
            font-size: 0.875rem;
            line-height: 1.8;
            color: var(--dark-gray);
            margin-bottom: 1.5rem;
        }

        .ms-footer-social {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .ms-footer-social a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 50%;
            background: var(--light-brown);
            color: var(--dark-brown);
            transition: all 0.3s ease;
        }

        .ms-footer-social a:hover {
            background: var(--medium-brown);
            color: var(--cream);
            transform: translateY(-3px);
        }

        .ms-footer-contact-item {
            display: flex;
            margin-bottom: 1rem;
            font-size: 0.875rem;
            color: var(--dark-gray);
            align-items: flex-start;
        }

        .ms-footer-contact-icon {
            margin-right: 0.75rem;
            color: var(--medium-brown);
            width: 1.25rem;
            text-align: center;
            font-size: 0.875rem;
            margin-top: 0.125rem;
        }
        
        .ms-map-container {
            width: 100%;
            height: 11.25rem;
            border-radius: 0.5rem;
            overflow: hidden;
            position: relative;
            border: 1px solid var(--light-brown);
            margin-top: 1.5rem;
        }
        
        .ms-map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
            filter: sepia(30%) contrast(95%);
        }

        .ms-footer-bottom {
            text-align: center;
            padding-top: 1.5rem;
            margin-top: 2rem;
            border-top: 1px solid var(--light-brown);
        }

        .ms-footer-bottom p {
            font-size: 0.8125rem;
            color: var(--medium-gray);
            margin: 0;
        }

        .ms-footer-bottom a {
            color: var(--dark-brown);
            text-decoration: none;
            transition: color 0.3s ease;
            font-weight: 500;
        }

        .ms-footer-bottom a:hover {
            color: var(--medium-brown);
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .ms-footer-row {
                grid-template-columns: 1fr 1fr;
            }
            
            .ms-footer {
                padding: 2.5rem 0 1.25rem;
            }
        }

        @media (max-width: 576px) {
            .ms-footer-row {
                grid-template-columns: 1fr;
            }
            
            .ms-footer {
                padding: 2rem 0 1rem;
            }
        }
    </style>
</head>
<body>

<!-- Footer Section -->
<footer class="ms-footer">
    <div class="ms-footer-container">
        <div class="ms-footer-row">
            <!-- Column 1: About -->
            <div class="ms-footer-col">
                <div class="ms-footer-logo">
                    <h2>Memorize Studio</h2>
                </div>
                <p class="ms-footer-about">
                    Abadikan momen spesialmu sendiri!
                    Nikmati pengalaman foto seru di self studio kami, dengan pencahayaan profesional dan berbagai properti yang bisa kamu atur sesuai gaya. 
                    Bebas berekspresi, hasil tetap maksimal!


                </p>
                <div class="ms-footer-social">
                    <a href="https://instagram.com/memorize.studio" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://wa.me/6281272997323" target="_blank" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    <a href="https://facebook.com/memorize.studio" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                </div>
            </div>

            <!-- Column 2: Contact -->
            <div class="ms-footer-col">
                <h4 class="ms-footer-title">Hubungi Kami</h4>
                <div class="ms-footer-contact">
                    <div class="ms-footer-contact-item">
                        <div class="ms-footer-contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            Jl. Darurruhama Plaju, Palembang, Sumatera Selatan, Indonesia
                        </div>
                    </div>
                    <div class="ms-footer-contact-item">
                        <div class="ms-footer-contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            +62 812 7299 7323
                        </div>
                    </div>
                    <div class="ms-footer-contact-item">
                        <div class="ms-footer-contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            contact@memorizestudio.com
                        </div>
                    </div>
                    <div class="ms-footer-contact-item">
                        <div class="ms-footer-contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            Setiap Hari: 08:00 - 20:00
                        </div>
                    </div>
                </div>
            </div>

            <!-- Column 3: Map -->
            <div class="ms-footer-col">
                <h4 class="ms-footer-title">Lokasi Studio</h4>
                <div class="ms-map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15946.120099834486!2d104.79900055!3d-3.00780005!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e3b751f8dd7d45b%3A0xabc1234567890def!2sJl.%20DI%20Pandjaitan%2C%20Plaju%20Ulu%2C%20Palembang!5e0!3m2!1sid!2sid!4v1715588000000!5m2!1sid!2sid"  allowfullscreen="" loading="lazy" title="Lokasi Memorize Studio"></iframe>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="ms-footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Memorize Studio. All rights reserved. 
               <a href="">Terms</a> | 
               <a href="">Privacy</a>
            </p>
        </div>
    </div>
</footer>

</body>
</html>