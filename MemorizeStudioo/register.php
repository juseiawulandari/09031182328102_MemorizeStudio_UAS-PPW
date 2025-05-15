<?php
// Koneksi ke database
$host = 'localhost'; // Host database
$dbname = 'memorize_studio'; // Nama database
$username = 'root'; // Username database
$password = ''; // Password database

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// Proses pendaftaran
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = htmlspecialchars($_POST['nama']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password

    // Validasi input
    if (empty($nama) || empty($email) || empty($_POST['password'])) {
        $error = "Semua field harus diisi!";
    } else {
        // Cek apakah email sudah terdaftar
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $error = "Email sudah terdaftar! <a href='login.php' class='text-white'>Login disini</a>";
        } else {
            // Simpan data ke database
            $stmt = $pdo->prepare("INSERT INTO users (nama, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$nama, $email, $password]);

            // Redirect ke halaman sukses atau login
            header("Location: login.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Memorize Studio</title>
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
            background: linear-gradient(135deg, var(--pale-brown) 0%, #ffffff 100%);
            color: var(--almost-black);
            line-height: 1.8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, var(--light-brown) 0%, transparent 70%);
            z-index: 0;
        }
        
        body::after {
            content: '';
            position: absolute;
            bottom: -100px;
            left: -100px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, var(--light-brown) 0%, transparent 70%);
            z-index: 0;
        }
        
        .register-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: var(--soft-shadow);
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            z-index: 1;
            transform: translateY(0);
            opacity: 1;
            transition: all 0.5s ease;
        }
        
        .register-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
        }
        
        .register-title {
            font-family: 'Playfair Display', serif;
            color: var(--dark-brown);
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-align: center;
            position: relative;
            padding-bottom: 15px;
        }
        
        .register-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, var(--medium-brown), var(--accent-color));
            border-radius: 2px;
        }
        
        .form-control {
            border: 1px solid var(--light-brown);
            padding: 0.75rem 1rem;
            border-radius: 6px;
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }
        
        .form-control:focus {
            border-color: var(--medium-brown);
            box-shadow: 0 0 0 0.25rem rgba(141, 110, 99, 0.25);
        }
        
        .btn-register {
            background: linear-gradient(135deg, var(--medium-brown) 0%, var(--dark-brown) 100%);
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            color: white;
            width: 100%;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(93, 64, 55, 0.2);
            color: white;
        }
        
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.9);
            color: white;
            border: none;
            border-radius: 6px;
            margin-bottom: 1.5rem;
        }
        
        .alert-danger a {
            color: white;
            text-decoration: underline;
            font-weight: 500;
        }
        
        .register-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--medium-gray);
        }
        
        .register-footer a {
            color: var(--dark-brown);
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .register-footer a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--dark-brown);
            transition: width 0.3s ease;
        }
        
        .register-footer a:hover::after {
            width: 100%;
        }
        
        .form-label {
            font-weight: 500;
            color: var(--dark-gray);
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .password-strength {
            height: 4px;
            background: var(--light-brown);
            border-radius: 2px;
            margin-top: -1.25rem;
            margin-bottom: 1rem;
            overflow: hidden;
        }
        
        .strength-meter {
            height: 100%;
            width: 0;
            background: var(--accent-color);
            transition: width 0.3s ease, background 0.3s ease;
        }
        
        /* Animations */
        .fade-in {
            animation: fadeIn 1s ease-out forwards;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }
            
            .register-container {
                padding: 2rem 1.5rem;
                margin-top: 0;
            }
            
            body::before, body::after {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container fade-in">
        <div class="register-container">
            <h2 class="register-title">Daftar Akun</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <div class="form-group">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required 
                           oninput="checkPasswordStrength(this.value)">
                    <div class="password-strength">
                        <div class="strength-meter" id="strengthMeter"></div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-register">Daftar</button>
            </form>

            <div class="register-footer">
                <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation triggers
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.fade-in');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = 1;
                    }
                });
            }, { threshold: 0.1 });
            
            elements.forEach(el => {
                observer.observe(el);
            });
        });

        // Password strength indicator
        function checkPasswordStrength(password) {
            const meter = document.getElementById('strengthMeter');
            const strength = calculatePasswordStrength(password);
            
            // Update meter width and color based on strength
            meter.style.width = strength * 25 + '%';
            
            if (strength < 2) {
                meter.style.backgroundColor = '#ff5252'; // Red
            } else if (strength < 4) {
                meter.style.backgroundColor = '#ffab91'; // Orange
            } else {
                meter.style.backgroundColor = '#4caf50'; // Green
            }
        }

        function calculatePasswordStrength(password) {
            let strength = 0;
            
            // Length contributes up to 3 points
            strength += Math.min(3, Math.floor(password.length / 3));
            
            // Contains special characters
            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength += 1;
            
            // Contains numbers
            if (/\d/.test(password)) strength += 1;
            
            // Contains both lowercase and uppercase
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 1;
            
            return Math.min(4, strength); // Max 4 points
        }
    </script>
</body>
</html>