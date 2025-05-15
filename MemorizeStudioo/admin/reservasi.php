<?php
require_once '../auth.php';

if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
    header("Location: ../login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "memorize_studio");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$reservasi = $conn->query("
    SELECT r.id, r.name, r.email, r.package, r.date, r.time, r.status, r.created_at,
           u.id as user_id, u.nama as user_nama 
    FROM reservasi r
    LEFT JOIN users u ON r.email = u.email
    ORDER BY r.date DESC, r.time DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola Reservasi | Memorize Studio</title>
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
        
        .badge-completed {
            background-color: #D1ECF1;
            color: #0C5460;
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
        
        .search-box {
            width: 250px;
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
            width: 180px; /* Customer */
            white-space: normal;
        }
        
        .table th:nth-child(3), 
        .table td:nth-child(3) {
            width: 180px; /* Email */
        }
        
        .table th:nth-child(4), 
        .table td:nth-child(4) {
            width: 150px; /* Package */
        }
        
        .table th:nth-child(5), 
        .table td:nth-child(5) {
            width: 150px; /* Date & Time */
        }
        
        .table th:nth-child(6), 
        .table td:nth-child(6) {
            width: 120px; /* Status */
        }
        
        .table th:nth-child(7), 
        .table td:nth-child(7) {
            width: 160px; /* Actions */
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
                    <a class="nav-link active" href="reservasi.php">
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
            <h1 class="h2">Kelola Reservasi</h1>
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

        <?php if(isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Reservasi</h5>
                <div class="d-flex gap-2">
                    <div class="input-group input-group-sm search-box">
                        <input type="text" class="form-control" placeholder="Cari...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                </div>
            </div>
            <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Package</th>
                    <th>Date & Time</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if($reservasi->num_rows > 0): ?>
                    <?php while($row = $reservasi->fetch_assoc()): ?>
                    <tr>
                        <td>#<?= $row['id'] ?></td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-truncate"><?= htmlspecialchars($row['name']) ?></span>
                                <small class="text-muted">
                                    <?= $row['user_id'] ? 'Member' : 'Non-member' ?>
                                </small>
                            </div>
                        </td>
                        <td class="text-truncate"><?= htmlspecialchars($row['email']) ?></td>
                        <td class="text-truncate"><?= htmlspecialchars($row['package']) ?></td>
                        <td>
                            <div class="d-flex flex-column">
                                <span><?= date('d M Y', strtotime($row['date'])) ?></span>
                                <small class="text-muted"><?= date('H:i', strtotime($row['time'])) ?></small>
                            </div>
                        </td>
                        <td>
                            <span class="badge 
                                <?= $row['status'] == 'confirmed' ? 'badge-confirmed' : 
                                   ($row['status'] == 'canceled' ? 'badge-canceled' : 'badge-pending') ?>">
                                <?= ucfirst($row['status']) ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="edit_reservasi.php?id=<?= $row['id'] ?>" 
                                   class="btn btn-sm btn-outline-primary" 
                                   data-bs-toggle="tooltip" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="hapus_reservasi.php?id=<?= $row['id'] ?>" 
                                   class="btn btn-sm btn-outline-danger" 
                                   data-bs-toggle="tooltip" title="Hapus"
                                   onclick="return confirm('Yakin ingin menghapus reservasi ini?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                                <?php if($row['status'] != 'confirmed' && $row['status'] != 'completed'): ?>
                                    <a href="konfirmasi_reservasi.php?id=<?= $row['id'] ?>" 
                                       class="btn btn-sm btn-outline-success" 
                                       data-bs-toggle="tooltip" title="Konfirmasi">
                                        <i class="bi bi-check-lg"></i>
                                    </a>
                                <?php endif; ?>
                               
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">Tidak ada data reservasi</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
            <div class="card-footer bg-light">
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm justify-content-center mb-0">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
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