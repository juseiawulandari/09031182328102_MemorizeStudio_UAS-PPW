<?php
require_once '../auth.php';

// Check if user is admin
if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
    header("Location: ../login.php");
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "memorize_studio");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get all users (except admin) with reservation info
$users = $conn->query("
    SELECT u.*, COUNT(r.id) as total_reservasi 
    FROM users u
    LEFT JOIN reservasi r ON u.email = r.email
    WHERE u.role = 'user' 
    GROUP BY u.id
    ORDER BY u.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manajemen User | Memorize Studio</title>
    
    <!-- CSS Links -->
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
        
        /* Sidebar Styles */
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
        
        /* Main Content Styles */
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
        
        /* Card Styles */
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
        
        /* Table Styles */
        .table {
            color: var(--dark-brown);
            margin-bottom: 0;
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
            color: var(--medium-brown);
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
        
        /* Badge Styles */
        .badge {
            font-weight: 500;
            padding: 0.5em 0.75em;
            font-size: 0.75em;
            border-radius: 0.5rem;
        }
        
        .badge-reservasi {
            background-color: rgba(141, 158, 110, 0.1);
            color: #8D9E6E;
        }
        
        /* User Avatar */
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--medium-brown);
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: bold;
        }
        
        /* Action Buttons */
        .action-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s;
        }
        
        .action-btn:hover {
            transform: scale(1.1);
        }
        
        .btn-view {
            background-color: rgba(110, 141, 158, 0.1);
            color: #6E8D9E;
            border: none;
        }
        
        .btn-view:hover {
            background-color: #6E8D9E;
            color: white;
        }
        
        .btn-delete {
            background-color: rgba(231, 74, 59, 0.1);
            color: var(--danger-color);
            border: none;
        }
        
        .btn-delete:hover {
            background-color: var(--danger-color);
            color: white;
        }
        
        /* Empty State */
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
        
        /* Floating Shape Animation */
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
        
        /* Responsive Adjustments */
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
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reservasi.php">
                        <i class="bi bi-calendar-check"></i> Reservasi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="users.php">
                        <i class="bi bi-people"></i> User
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="portofolio.php">
                        <i class="bi bi-images"></i> Portofolio
                    </a>
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
            <h1 class="h2">Manajemen User</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
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

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-people me-2"></i> Daftar User</h5>
                <span class="badge" style="background-color: var(--medium-brown); color: white;">
                    <?= $users->num_rows ?> User Terdaftar
                </span>
            </div>
            
            <div class="card-body p-0">
                <?php if($users->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Reservasi</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $users->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="user-avatar">
                                                <?= strtoupper(substr($row['nama'], 0, 1)) ?>
                                            </span>
                                            <?= htmlspecialchars($row['nama']) ?>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                                    <td>
                                        <span class="badge badge-reservasi">
                                            <i class="bi bi-calendar-check me-1"></i>
                                            <?= $row['total_reservasi'] ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="lihat_reservasi.php?user_id=<?= $row['id'] ?>" 
                                               class="action-btn btn-view" 
                                               data-bs-toggle="tooltip" 
                                               title="Lihat Reservasi">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="hapus_user.php?id=<?= $row['id'] ?>" 
                                               class="action-btn btn-delete" 
                                               onclick="return confirm('Yakin ingin menghapus user ini? Semua reservasinya juga akan dihapus!')"
                                               data-bs-toggle="tooltip" 
                                               title="Hapus User">
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
                        <i class="bi bi-people"></i>
                        <h5 class="mt-3">Belum Ada User Terdaftar</h5>
                        <p class="text-muted">Tidak ada user yang ditemukan dalam sistem</p>
                        <a href="add_user.php" class="btn btn-primary mt-2" style="background-color: var(--medium-brown); border-color: var(--medium-brown);">
                            <i class="bi bi-plus-circle me-1"></i> Tambah User Pertama
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Floating Shape -->
        <div class="floating-shape shape-1"></div>
    </main>
</div>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
</body>
</html>