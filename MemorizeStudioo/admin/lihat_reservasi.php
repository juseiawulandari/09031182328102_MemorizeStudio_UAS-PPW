<?php
require_once '../auth.php';

if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    header("Location: users.php");
    exit;
}

$user_id = (int)$_GET['user_id'];

$conn = new mysqli("localhost", "root", "", "memorize_studio");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data user
$user_query = $conn->prepare("SELECT id, nama, email FROM users WHERE id = ?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user_result = $user_query->get_result();

if ($user_result->num_rows === 0) {
    header("Location: users.php");
    exit;
}

$user = $user_result->fetch_assoc();

// Ambil reservasi user
$reservasi_query = $conn->prepare("
    SELECT id, name, package, date, time, status, created_at 
    FROM reservasi 
    WHERE email = ?
    ORDER BY date DESC, time DESC
");
$reservasi_query->bind_param("s", $user['email']);
$reservasi_query->execute();
$reservasi = $reservasi_query->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Reservasi User | Memorize Studio</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
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
        
        .user-info-card {
            border-left: 0.25rem solid var(--accent);
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
        
        .stat-badge {
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            display: inline-flex;
            align-items: center;
        }
        
        .stat-badge i {
            margin-right: 0.5rem;
        }
        
        .stat-badge.confirmed {
            background-color: rgba(28, 200, 138, 0.1);
            color: #155724;
        }
        
        .stat-badge.pending {
            background-color: rgba(246, 194, 62, 0.1);
            color: #856404;
        }
        
        .stat-badge.canceled {
            background-color: rgba(231, 74, 59, 0.1);
            color: #721C24;
        }
        
        .action-buttons .btn {
            width: 2.25rem;
            height: 2.25rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }
        
        .action-buttons .btn:hover {
            transform: translateY(-2px);
        }
        
        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        .empty-state {
            padding: 3rem;
            text-align: center;
            color: var(--medium-brown);
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--light-brown);
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

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table {
            table-layout: fixed;
            width: 100%;
        }
        
        .table th, .table td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            padding: 0.75rem 1rem;
        }
        
        /* Atur lebar kolom */
        .table th:nth-child(1), 
        .table td:nth-child(1) {
            width: 70px; /* ID */
        }
        
        .table th:nth-child(2), 
        .table td:nth-child(2) {
            width: 180px; /* Nama */
        }
        
        .table th:nth-child(3), 
        .table td:nth-child(3) {
            width: 150px; /* Package */
        }
        
        .table th:nth-child(4), 
        .table td:nth-child(4) {
            width: 120px; /* Date */
        }
        
        .table th:nth-child(5), 
        .table td:nth-child(5) {
            width: 100px; /* Time */
        }
        
        .table th:nth-child(6), 
        .table td:nth-child(6) {
            width: 120px; /* Status */
        }
        
        .table th:nth-child(7), 
        .table td:nth-child(7) {
            width: 150px; /* Created At */
        }
        
        .table th:nth-child(8), 
        .table td:nth-child(8) {
            width: 100px; /* Actions */
        }
        
        /* Untuk tampilan mobile */
        @media (max-width: 768px) {
            .table th, .table td {
                padding: 0.5rem;
                font-size: 0.85rem;
            }
            
            .action-buttons .btn {
                width: 1.75rem;
                height: 1.75rem;
                font-size: 0.75rem;
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
                    <a class="nav-link" href="reservasi.php">
                        <i class="bi bi-calendar-check"></i>Reservasi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="users.php">
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
            <h1 class="h2">Reservasi User</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="users.php" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar User
                </a>
            </div>
        </div>

        <?php if(isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show" role="alert">
                <i class="bi <?= 
                    $_SESSION['message_type'] == 'success' ? 'bi-check-circle-fill' : 
                    ($_SESSION['message_type'] == 'danger' ? 'bi-exclamation-triangle-fill' : 'bi-info-circle-fill')
                ?> me-2"></i>
                <?= $_SESSION['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <!-- User Info Card -->
        <div class="card mb-4 user-info-card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="card-title"><i class="bi bi-person me-2"></i>Informasi User</h5>
                        <div class="d-flex align-items-center mb-3">
                            <div class="user-avatar bg-primary text-white me-3">
                                <?= strtoupper(substr($user['nama'], 0, 1)) ?>
                            </div>
                            <div>
                                <h6 class="mb-1"><?= htmlspecialchars($user['nama']) ?></h6>
                                <small class="text-muted"><?= htmlspecialchars($user['email']) ?></small>
                            </div>
                        </div>
                        <p class="mb-0"><strong>ID User:</strong> <?= $user['id'] ?></p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="card-title"><i class="bi bi-graph-up me-2"></i>Statistik Reservasi</h5>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="stat-badge">
                                <i class="bi bi-calendar-check me-1"></i>
                                Total: <?= $reservasi->num_rows ?>
                            </span>
                            <?php
                            $confirmed = 0;
                            $pending = 0;
                            $canceled = 0;
                            
                            if ($reservasi->num_rows > 0) {
                                while($row = $reservasi->fetch_assoc()) {
                                    if ($row['status'] == 'confirmed') $confirmed++;
                                    elseif ($row['status'] == 'pending') $pending++;
                                    elseif ($row['status'] == 'canceled') $canceled++;
                                }
                                $reservasi->data_seek(0); // Reset pointer
                            }
                            ?>
                            <span class="stat-badge confirmed">
                                <i class="bi bi-check-circle me-1"></i>
                                Dikonfirmasi: <?= $confirmed ?>
                            </span>
                            <span class="stat-badge pending">
                                <i class="bi bi-hourglass-split me-1"></i>
                                Pending: <?= $pending ?>
                            </span>
                            <span class="stat-badge canceled">
                                <i class="bi bi-x-circle me-1"></i>
                                Dibatalkan: <?= $canceled ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reservasi Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i>Daftar Reservasi</h5>
            </div>
            
            <div class="card-body p-0">
                <?php if($reservasi->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Paket</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Status</th>
                                    <th>Dibuat Pada</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $reservasi->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?= $row['id'] ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['package']) ?></td>
                                    <td><?= date('d M Y', strtotime($row['date'])) ?></td>
                                    <td><?= date('H:i', strtotime($row['time'])) ?></td>
                                    <td>
                                        <span class="badge 
                                            <?= $row['status'] == 'confirmed' ? 'badge-confirmed' : 
                                               ($row['status'] == 'canceled' ? 'badge-canceled' : 'badge-pending') ?>">
                                            <?= ucfirst($row['status']) ?>
                                        </span>
                                    </td>
                                    <td><?= date('d M Y H:i', strtotime($row['created_at'])) ?></td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1 action-buttons">
                                            <a href="edit_reservasi.php?id=<?= $row['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary" 
                                               data-bs-toggle="tooltip" 
                                               title="Edit Reservasi">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="hapus_reservasi.php?id=<?= $row['id'] ?>" 
                                               class="btn btn-sm btn-outline-danger" 
                                               onclick="return confirm('Yakin ingin menghapus reservasi ini?')"
                                               data-bs-toggle="tooltip" 
                                               title="Hapus Reservasi">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="bi bi-calendar-x"></i>
                        <h5 class="mt-3">Belum Ada Reservasi</h5>
                        <p class="text-muted">User ini belum memiliki reservasi</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Aktifkan tooltip
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
</body>
</html>