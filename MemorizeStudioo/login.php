<?php
session_start();

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "memorize_studio");
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Konfigurasi Admin (sebaiknya simpan di file terpisah atau environment variable)
$admin_config = [
    'email' => 'admin@memorize.com',
    'password' => password_hash('12345', PASSWORD_BCRYPT) // Password di-hash
];

// Proses login
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi input
    if (empty($_POST['email'])) {
        $error = "Email harus diisi";
    } elseif (empty($_POST['password'])) {
        $error = "Password harus diisi";
    } else {
        // Escape input untuk mencegah SQL injection
        $email = $conn->real_escape_string($_POST['email']);
        $password = $_POST['password'];

        // Cek login sebagai admin
        if ($email === $admin_config['email']) {
            if (password_verify($password, $admin_config['password'])) {
                // Set session untuk admin
                $_SESSION['user_id'] = 0; // ID khusus admin
                $_SESSION['user_email'] = $email;
                $_SESSION['name'] = "Administrator";
                $_SESSION['role'] = 'admin';
                
                // Regenerate session ID untuk mencegah session fixation
                session_regenerate_id(true);
                
                header("Location: admin/dashboard.php");
                exit;
            } else {
                $error = "Password admin salah!";
            }
        } else {
            // Proses login user biasa menggunakan prepared statement
            $stmt = $conn->prepare("SELECT id, nama, email, password, role FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    // Set session untuk user biasa
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['user_email'] = $row['email'];
                    $_SESSION['name'] = $row['nama'];
                    $_SESSION['role'] = $row['role'] ?? 'user';
                    
                    // Regenerate session ID
                    session_regenerate_id(true);
                    
                    // Redirect berdasarkan role
                    if ($_SESSION['role'] === 'admin') {
                        header("Location: admin/dashboard.php");
                    } else {
                        header("Location: index.php");
                    }
                    exit;
                } else {
                    $error = "Password salah! <a href='login.php' class='text-white'>Coba lagi</a>.";
                }
            } else {
                $error = "Email tidak ditemukan! <a href='register.php' class='text-white'>Daftar dulu</a>.";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Memorize Studio</title>
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
        
        .login-container {
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
        
        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
        }
        
        .login-title {
            font-family: 'Playfair Display', serif;
            color: var(--dark-brown);
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-align: center;
            position: relative;
            padding-bottom: 15px;
        }
        
        .login-title::after {
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
        }
        
        .form-control:focus {
            border-color: var(--medium-brown);
            box-shadow: 0 0 0 0.25rem rgba(141, 110, 99, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--medium-brown) 0%, var(--dark-brown) 100%);
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(93, 64, 55, 0.2);
        }
        
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.9);
            color: white;
            border: none;
            border-radius: 6px;
        }
        
        .alert-danger a {
            color: white;
            text-decoration: underline;
            font-weight: 500;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--medium-gray);
        }
        
        .login-footer a {
            color: var(--dark-brown);
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .login-footer a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--dark-brown);
            transition: width 0.3s ease;
        }
        
        .login-footer a:hover::after {
            width: 100%;
        }
        
        .form-label {
            font-weight: 500;
            color: var(--dark-gray);
            margin-bottom: 0.5rem;
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
            
            .login-container {
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
        <div class="login-container">
            <h2 class="login-title">Login</h2>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger mb-4"><?= $error ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="mb-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required 
                           value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-login btn-primary w-100 text-white">Masuk</button>
            </form>

            <div class="login-footer">
                <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
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
    </script>
</body>
</html>