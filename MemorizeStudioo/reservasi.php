<?php
require_once 'auth.php';
// Cek apakah user sudah login
$isLoggedIn = false;
$username = '';

// Fungsi untuk cek login (pastikan ada di auth.php)
if (function_exists('isLoggedIn')) {
    $isLoggedIn = isLoggedIn();
    if ($isLoggedIn && function_exists('getUserName')) {
        $username = getUserName();
    }
}

// Redirect jika belum login
if (!$isLoggedIn) {
    header("Location: login.php");
    exit;
}

// Dapatkan user_id dari session
$user_id = $_SESSION['user_id'] ?? null;
$email = $_SESSION['user_email'] ?? '';

// Koneksi ke database
    $conn = new mysqli("localhost", "root", "", "memorize_studio");

    // Cek koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

// Fungsi untuk memproses form ketika di-submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $package = $_POST["package"];
    $date = $_POST["date"];
    $time = $_POST["time"];

    
    // Validasi jam operasional (08:00-20:00)
    $jam_reservasi = date('H:i', strtotime($time));
    $jam_buka = '08:00';
    $jam_tutup = '20:00';
    
    if ($jam_reservasi < $jam_buka || $jam_reservasi > $jam_tutup) {
        echo "<script>alert('Reservasi hanya bisa dilakukan antara jam 08:00 sampai 20:00');</script>";
        exit;
    }

    // Validasi batas waktu 3 bulan ke depan
    $max_date = date('Y-m-d', strtotime('+3 months'));
    if ($date > $max_date) {
        echo "<script>alert('Reservasi hanya bisa dilakukan maksimal 3 bulan ke depan');</script>";
        exit;
    }

    // Cek apakah slot waktu sudah dipesan
    $cek = $conn->prepare("SELECT id FROM reservasi WHERE date = ? AND time = ?");
    $cek->bind_param("ss", $date, $time);
    $cek->execute();
    $cek->store_result();
    
    if ($cek->num_rows > 0) {
        echo "<script>alert('Slot waktu ini sudah dipesan');</script>";
        $cek->close();
        exit;
    }
    $cek->close();

    // Query untuk menyimpan data
    $sql = "INSERT INTO reservasi (user_id, name, email, package, date, time, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $user_id, $name, $email, $package, $date, $time);

    if ($stmt->execute()) {
        echo "<script>alert('Reservasi berhasil!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
    $conn->close();
    exit;
}

// Generate pilihan jam
$time_slots = [];
$start = strtotime('08:00');
$end = strtotime('20:00');
for ($i = $start; $i <= $end; $i += 1800) { // 1800 detik = 30 menit
    $time_slots[] = date('H:i', $i);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memorize Studio - Reservasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
        
        .reservation-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }
        
        .reservation-header {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
            padding-bottom: 1rem;
        }
        
        .reservation-header h2 {
            color: var(--dark-brown);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .reservation-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 25%;
            width: 50%;
            height: 3px;
            background: linear-gradient(90deg, var(--light-brown), var(--dark-brown), var(--light-brown));
            border-radius: 3px;
        }
        
        .form-label {
            font-weight: 500;
            color: var(--dark-gray);
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid var(--light-brown);
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--dark-brown);
            box-shadow: 0 0 0 0.25rem rgba(93, 64, 55, 0.15);
        }
        
        .btn-reservation {
            background-color: var(--dark-brown);
            color: white;
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border: none;
            text-transform: uppercase;
            font-size: 0.9rem;
        }
        
        .btn-reservation:hover {
            background-color: var(--medium-brown);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(93, 64, 55, 0.2);
        }
        
        .package-option {
            padding: 1rem;
            margin-bottom: 0.5rem;
            border-radius: 8px;
            background-color: var(--pale-brown);
            transition: all 0.3s ease;
        }
        
        .package-option:hover {
            background-color: var(--light-brown);
            cursor: pointer;
        }
        
        .package-option.selected {
            background-color: var(--dark-brown);
            color: white;
        }
        
        .time-slot {
            display: inline-block;
            padding: 0.5rem 1rem;
            margin: 0.25rem;
            background-color: var(--pale-brown);
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .time-slot:hover {
            background-color: var(--light-brown);
        }
        
        .time-slot.selected {
            background-color: var(--dark-brown);
            color: white;
        }
        
        .form-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background-color: var(--pale-brown);
            border-radius: 10px;
        }
        
        .form-section-title {
            color: var(--dark-brown);
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }
        
        .form-section-title i {
            margin-right: 0.75rem;
            font-size: 1.25rem;
        }
        
        @media (max-width: 768px) {
            .reservation-container {
                padding: 1.5rem;
                margin: 1rem;
            }
            
            .reservation-header h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

    <div class="reservation-container">
        <div class="reservation-header">
            <h2><i class="fas fa-calendar-alt me-2"></i>Reservasi Studio</h2>
            <p class="text-muted">Isi formulir di bawah ini untuk melakukan reservasi</p>
        </div>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-section">
                <h5 class="form-section-title"><i class="fas fa-user"></i> Informasi Pribadi</h5>
                <div class="mb-4">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama lengkap Anda" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
            </div>
            
            <div class="form-section">
                <h5 class="form-section-title"><i class="fas fa-box-open"></i> Pilihan Paket</h5>
                <select class="form-select mb-4" id="package" name="package" required>
                    <option value="" disabled selected>-- Pilih Paket --</option>
                    <option value="Paket Basic">Paket Basic - Rp 90.000 (1 jam)</option>
                    <option value="Paket Premium">Paket Standard - Rp 210.000 (2 jam)</option>
                    <option value="Paket Eksklusif">Paket Premium - Rp 485.000 (4 jam)</option>
                </select>
                
                <div class="package-descriptions">
                    <div class="alert alert-light" role="alert">
                        <small>
                            <strong>Paket Basic:</strong> Maksimal 2 Orang, 1 Jam per Sesi, Free Soft File, dan Free 2 Cetak Foto<br>
                            <strong>Paket Standard:</strong> Maksimal 4 Orang, 2 Jam per Sesi, Free Soft File, dan Free 4 Cetak Foto<br>
                            <strong>Paket Premium:</strong> Maksimal 20 Orang, 4 Jam per Sesi, Free Soft File, dan Free 10 Cetak Foto
                        </small>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h5 class="form-section-title"><i class="far fa-calendar-check"></i> Jadwal Reservasi</h5>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="date" class="form-label">Tanggal Reservasi</label>
                        <input type="date" class="form-control" id="date" name="date" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+3 months')); ?>" required>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="time" class="form-label">Waktu Reservasi</label>
                        <select class="form-select" id="time" name="time" required>
                            <?php foreach ($time_slots as $slot): ?>
                                <option value="<?php echo $slot; ?>"><?php echo $slot; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="time-slots-info alert alert-light">
                    <small><i class="fas fa-info-circle me-2"></i> Studio buka setiap hari pukul 08:00 - 20:00. Reservasi maksimal 3 bulan ke depan.</small>
                </div>
            </div>
            
            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-reservation">
                    <i class="fas fa-paper-plane me-2"></i>Konfirmasi Reservasi
                </button>
            </div>
        </form>
    </div>

<?php require 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update jam yang tersedia saat tanggal dipilih
        document.getElementById('date').addEventListener('change', function() {
            const date = this.value;
            if (!date) return;
            
            fetch('get_available_time.php?date=' + date)
                .then(response => response.json())
                .then(data => {
                    const timeSelect = document.getElementById('time');
                    timeSelect.innerHTML = '';
                    
                    data.availableTimes.forEach(time => {
                        const option = document.createElement('option');
                        option.value = time;
                        option.textContent = time;
                        timeSelect.appendChild(option);
                    });
                });
        });
        
        // Menambahkan efek interaktif pada pilihan paket
        document.querySelectorAll('#package option').forEach(option => {
            option.addEventListener('mouseover', function() {
                this.style.backgroundColor = '#D7CCC8';
            });
            option.addEventListener('mouseout', function() {
                this.style.backgroundColor = '';
            });
        });
    </script>
</body>
</html>