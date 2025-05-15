<?php
require_once '../auth.php';

// Cek admin
if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
    header("Location: ../login.php");
    exit;
}

// Koneksi database
$conn = new mysqli("localhost", "root", "", "memorize_studio");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data reservasi
$id = $_GET['id'];
$reservasi = $conn->query("
    SELECT r.*, u.nama 
    FROM reservasi r
    LEFT JOIN users u ON r.email = u.email
    WHERE r.id = $id
")->fetch_assoc();

// Proses update status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE reservasi SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Status reservasi berhasil diupdate!";
        header("Location: reservasi.php");
        exit;
    } else {
        $_SESSION['error'] = "Gagal update status: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Reservasi - Memorize Studio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --dark-brown: #5D4037;
            --medium-brown: #8D6E63;
            --light-brown: #D7CCC8;
            --pale-brown: #EFEBE9;
            --cream: #FAF9F6;
            --accent: #FFAB91;
            --gold: #FFD700;
            --soft-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
        
        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--cream);
            color: var(--dark-brown);
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--dark-brown) 0%, var(--medium-brown) 100%);
            box-shadow: var(--soft-shadow);
            color: white;
            position: fixed;
            width: 250px;
            z-index: 1000;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 0.75rem 1.5rem;
            margin: 0.25rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            color: white;
            background-color: var(--accent);
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(255, 171, 145, 0.3);
        }
        
        .sidebar .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }
        
        .sidebar-brand {
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 1rem;
        }
        
        .sidebar-brand i {
            color: var(--gold);
            margin-right: 0.75rem;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 2rem 2.5rem;
        }
        
        .page-header {
            border-bottom: 1px solid var(--light-brown);
            padding-bottom: 1.5rem;
            margin-bottom: 2.5rem;
        }
        
        .page-header h1 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--dark-brown);
            position: relative;
        }
        
        .page-header h1::after {
            content: '';
            position: absolute;
            bottom: -1.5rem;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, var(--medium-brown), var(--accent));
        }
        
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: var(--soft-shadow);
            margin-bottom: 1.75rem;
            border: 1px solid rgba(215, 204, 200, 0.3);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid var(--light-brown);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            color: var(--dark-brown);
            border-radius: 0.75rem 0.75rem 0 0 !important;
        }
        
        .badge {
            font-weight: 500;
            padding: 0.5em 0.75em;
            font-size: 0.75em;
            border-radius: 0.5rem;
        }
        
        .badge-pending {
            background-color: #FFF3CD;
            color: #856404;
        }
        
        .badge-confirmed {
            background-color: #D4EDDA;
            color: #155724;
        }
        
        .badge-canceled {
            background-color: #F8D7DA;
            color: #721C24;
        }
        
        .btn-primary {
            background-color: var(--medium-brown);
            border-color: var(--medium-brown);
        }
        
        .btn-primary:hover {
            background-color: var(--dark-brown);
            border-color: var(--dark-brown);
        }
        
        .form-control, .form-select {
            border-radius: 0.5rem;
            border: 1px solid var(--light-brown);
            padding: 0.5rem 1rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--medium-brown);
            box-shadow: 0 0 0 0.25rem rgba(141, 110, 99, 0.25);
        }
        
        .input-group .form-control {
            border-radius: 0.5rem 0 0 0.5rem;
        }
        
        .input-group .form-control:last-child {
            border-radius: 0 0.5rem 0.5rem 0;
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .sidebar {
                width: 220px;
            }
            .main-content {
                margin-left: 220px;
                padding: 1.5rem;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                height: auto;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="bi bi-camera"></i>
            <span>Memorize Studio</span>
        </div>
        <div class="p-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">
                        <i class="bi bi-speedometer2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="reservasi.php">
                        <i class="bi bi-calendar-check"></i> Reservasi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="users.php">
                        <i class="bi bi-people"></i> User
                    </a>
                <li class="nav-item">
                    <a class="nav-link" href="portofolio.php">
                        <i class="bi bi-images"></i> Portofolio
                    </a>
                </li>
                </li>
                <li class="nav-item mt-4">
                    <a class="nav-link text-white-50" href="../logout.php">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
            <h1 class="h2">Edit Reservasi</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="reservasi.php" class="btn btn-sm btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Reservasi #<?= $reservasi['id'] ?? '' ?></h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">ID Reservasi</label>
                                <input type="text" class="form-control" value="<?= $reservasi['id'] ?? '' ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Pemesan</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($reservasi['name'] ?? '') ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($reservasi['email'] ?? '') ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Paket</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($reservasi['package'] ?? '') ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal & Waktu</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="<?= date('d M Y', strtotime($reservasi['date'] ?? '')) ?>" readonly>
                                    <input type="text" class="form-control" value="<?= date('H:i', strtotime($reservasi['time'] ?? '')) ?>" readonly>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="pending" <?= ($reservasi['status'] ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="confirmed" <?= ($reservasi['status'] ?? '') == 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                    <option value="canceled" <?= ($reservasi['status'] ?? '') == 'canceled' ? 'selected' : '' ?>>Canceled</option>
                                </select>
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" name="update" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i> Update Status
                                </button>
                                <a href="reservasi.php" class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Detail Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge 
                                <?= ($reservasi['status'] ?? 'pending') == 'confirmed' ? 'badge-confirmed' : 
                                    (($reservasi['status'] ?? 'pending') == 'canceled' ? 'badge-canceled' : 'badge-pending') ?>
                                me-2">
                                <?= ucfirst($reservasi['status'] ?? 'pending') ?>
                            </span>
                            <span>Status saat ini</span>
                        </div>
                        <p class="small text-muted">
                            Pilih status baru untuk reservasi ini. Pastikan untuk mengkonfirmasi dengan customer sebelum mengubah status.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>