<?php
require_once '../auth.php';

if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
    header("Location: ../login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "memorize_studio");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$reservasi = $conn->query("
    SELECT * FROM reservasi
    ORDER BY date DESC, time DESC
    LIMIT 5
");

$users = $conn->query("SELECT * FROM users WHERE role = 'user'");

$today = $conn->query("SELECT COUNT(*) FROM reservasi WHERE date = CURDATE()")->fetch_row()[0];
$pending = $conn->query("SELECT COUNT(*) FROM reservasi WHERE status = 'pending'")->fetch_row()[0];
$total_reservations = $conn->query("SELECT COUNT(*) FROM reservasi")->fetch_row()[0];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Memorize Studio</title>
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
        
        .stat-card {
            border-left: 4px solid;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }
        
        .stat-card.primary {
            border-left-color: var(--medium-brown);
        }
        
        .stat-card.success {
            border-left-color: #8D9E6E;
        }
        
        .stat-card.warning {
            border-left-color: #D7A35F;
        }
        
        .stat-card.info {
            border-left-color: #6E8D9E;
        }
        
        .stat-card .card-title {
            text-transform: uppercase;
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--medium-gray);
            letter-spacing: 0.05rem;
        }
        
        .stat-card .card-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark-brown);
            margin: 0.5rem 0;
        }
        
        .stat-card .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        
        .stat-card.primary .card-icon {
            background-color: rgba(141, 110, 99, 0.1);
            color: var(--medium-brown);
        }
        
        .stat-card.success .card-icon {
            background-color: rgba(141, 158, 110, 0.1);
            color: #8D9E6E;
        }
        
        .stat-card.warning .card-icon {
            background-color: rgba(215, 163, 95, 0.1);
            color: #D7A35F;
        }
        
        .stat-card.info .card-icon {
            background-color: rgba(110, 141, 158, 0.1);
            color: #6E8D9E;
        }
        
        .table {
            color: var(--dark-brown);
            margin-bottom: 0;
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
            color: var(--medium-gray);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05rem;
            padding: 1rem 1.5rem;
            background-color: var(--pale-brown);
        }
        
        .table td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
            border-top: 1px solid var(--light-brown);
        }
        
        .table tr:last-child td {
            border-bottom: none;
        }
        
        .table tr:hover td {
            background-color: rgba(239, 235, 233, 0.5);
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
        
        .progress {
            height: 10px;
            border-radius: 5px;
            background-color: var(--light-brown);
        }
        
        .progress-bar {
            border-radius: 5px;
        }
        
        .floating-shape {
            position: absolute;
            opacity: 0.1;
            z-index: 0;
        }
        
        .shape-1 {
            top: 20%;
            right: 5%;
            width: 100px;
            height: 100px;
            background-color: var(--medium-brown);
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            animation: float 8s ease-in-out infinite;
        }
        
        @keyframes float {
            0% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
            100% { transform: translateY(0) rotate(0deg); }
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
                    <a class="nav-link active" href="dashboard.php">
                        <i class="bi bi-speedometer2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reservasi.php">
                        <i class="bi bi-calendar-check"></i>Reservasi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="users.php">
                        <i class="bi bi-people"></i>User
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="portofolio.php">
                        <i class="bi bi-images"></i>Portofolio
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <a class="nav-link text-white-50" href="../logout.php">
                        <i class="bi bi-box-arrow-right"></i>Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
            <h1 class="h2">Dashboard Admin</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-download me-1"></i>Export
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-printer me-1"></i>Print
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistik -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card primary h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Total Reservations</h5>
                                <p class="card-value"><?= $total_reservations ?></p>
                                <p class="text-muted small mb-0"><i class="bi bi-arrow-up text-success"></i> 12% from last month</p>
                            </div>
                            <div class="card-icon">
                                <i class="bi bi-calendar"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card success h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Today's Reservations</h5>
                                <p class="card-value"><?= $today ?></p>
                                <p class="text-muted small mb-0"><i class="bi bi-arrow-up text-success"></i> 3 new today</p>
                            </div>
                            <div class="card-icon">
                                <i class="bi bi-calendar-day"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card warning h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Pending Reservations</h5>
                                <p class="card-value"><?= $pending ?></p>
                                <p class="text-muted small mb-0"><i class="bi bi-clock-history text-warning"></i> Needs approval</p>
                            </div>
                            <div class="card-icon">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card info h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Total Users</h5>
                                <p class="card-value"><?= $users->num_rows ?></p>
                                <p class="text-muted small mb-0"><i class="bi bi-person-plus text-info"></i> 5 new this week</p>
                            </div>
                            <div class="card-icon">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Reservasi Terbaru -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Latest Reservations</h5>
                <a href="reservasi.php" class="btn btn-sm btn-primary">
                    <i class="bi bi-list-ul me-1"></i> View All
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Package</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $reservasi->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?= $row['id'] ?></td>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($row['name']) ?></div>
                                        <div class="text-muted small"><?= htmlspecialchars($row['email']) ?></div>
                                    </td>
                                    <td><?= htmlspecialchars($row['package']) ?></td>
                                    <td>
                                        <div><?= date('d M Y', strtotime($row['date'])) ?></div>
                                        <div class="text-muted small"><?= date('H:i', strtotime($row['time'])) ?></div>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            <?= ($row['status'] ?? 'pending') == 'confirmed' ? 'badge-confirmed' : 
                                               (($row['status'] ?? 'pending') == 'canceled' ? 'badge-canceled' : 'badge-pending') ?>">
                                            <?= ucfirst($row['status'] ?? 'pending') ?>
                                        </span>
                                    </td>
                                    
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        
        
        <!-- Floating Shape -->
        <div class="floating-shape shape-1"></div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Enable tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
</body>
</html>