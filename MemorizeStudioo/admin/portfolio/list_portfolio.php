<?php
require_once '../../auth.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
    header("Location: ../../login.php");
    exit;
}

require_once '../../config/database.php';
require_once '../../includes/functions.php';

// Ambil semua item portofolio
$portfolio_items = getAllPortfolioItems($conn);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Portofolio - Memorize Studio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
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
            line-height: 1.6;
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
            padding: 2rem 0;
            width: calc(100% - 250px);
        }
        
        .container-fluid {
            padding: 0 2rem;
        }
        
        .page-header {
            border-bottom: 1px solid var(--light-brown);
            padding-bottom: 1.5rem;
            margin-bottom: 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .page-header h1 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--dark-brown);
            position: relative;
            margin-bottom: 0;
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
        
        /* Table Styles */
        .table-container {
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: var(--soft-shadow);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .table {
            color: var(--dark-brown);
            margin-bottom: 0;
            width: 100%;
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
            background-color: var(--pale-brown);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05rem;
            padding: 1rem 1.5rem;
            white-space: nowrap;
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
        
        /* Button Styles */
        .btn {
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn i {
            margin-right: 0.5rem;
        }
        
        .btn-primary {
            background-color: var(--medium-brown);
            border-color: var(--medium-brown);
        }
        
        .btn-primary:hover {
            background-color: var(--dark-brown);
            border-color: var(--dark-brown);
            transform: translateY(-1px);
        }
        
        .btn-secondary {
            background-color: var(--light-brown);
            border-color: var(--light-brown);
            color: var(--dark-brown);
        }
        
        .btn-secondary:hover {
            background-color: #c8b7b0;
            border-color: #c8b7b0;
            transform: translateY(-1px);
        }
        
        .btn-warning {
            background-color: #FFD166;
            border-color: #FFD166;
        }
        
        .btn-danger {
            background-color: #EF476F;
            border-color: #EF476F;
        }
        
        .btn-sm {
            padding: 0.35rem 0.75rem;
            font-size: 0.875rem;
        }
        
        /* Alert Styles */
        .alert {
            border-radius: 0.5rem;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        /* Portfolio Image Styles */
        .portfolio-img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid var(--light-brown);
            transition: transform 0.3s ease;
        }
        
        .portfolio-img:hover {
            transform: scale(1.05);
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        /* Description Cell */
        .description-cell {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Empty State */
        .empty-state {
            padding: 3rem;
            text-align: center;
            color: var(--medium-brown);
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .sidebar {
                width: 220px;
            }
            .main-content {
                margin-left: 220px;
                width: calc(100% - 220px);
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                height: auto;
                min-height: auto;
            }
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 1rem 0;
            }
            
            .container-fluid {
                padding: 0 1rem;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
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
                    <a class="nav-link" href="../dashboard.php">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../reservasi.php">
                        <i class="bi bi-calendar-check"></i> Reservasi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../users.php">
                        <i class="bi bi-people"></i> User
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="list_portfolio.php">
                        <i class="bi bi-images"></i> Portofolio
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <a class="nav-link text-white-50" href="../../logout.php">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid">
            <div class="page-header">
                <h1>Kelola Portofolio</h1>
                <div class="d-flex gap-2">
                    <a href="../dashboard.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                    <a href="add_portfolio.php" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Baru
                    </a>
                </div>
            </div>
            
            <!-- Success/Error Messages -->
            <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_GET['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_GET['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
            
            <!-- Portfolio Table -->
            <div class="table-container">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Gambar</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>Paket</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($portfolio_items)): ?>
                        <tr>
                            <td colspan="7" class="empty-state">
                                <i class="bi bi-images" style="font-size: 2rem;"></i>
                                <p class="mt-2">Belum ada data portofolio</p>
                                <a href="add_portfolio.php" class="btn btn-primary mt-2">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah Portofolio Pertama
                                </a>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($portfolio_items as $item): ?>
                        <tr>
                            <td><?= $item['id'] ?></td>
                            <td>
                                <img src="../../<?= htmlspecialchars($item['image_path']) ?>" 
                                     alt="<?= htmlspecialchars($item['title']) ?>" 
                                     class="portfolio-img"
                                     data-bs-toggle="tooltip" 
                                     data-bs-title="Lihat gambar">
                            </td>
                            <td><?= htmlspecialchars($item['title']) ?></td>
                            <td class="description-cell" title="<?= htmlspecialchars($item['description']) ?>">
                                <?= htmlspecialchars(substr($item['description'], 0, 50)) ?>...
                            </td>
                            <td><?= htmlspecialchars($item['package_type']) ?></td>
                            <td><?= date('d M Y', strtotime($item['created_at'])) ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit_portfolio.php?id=<?= $item['id'] ?>" 
                                       class="btn btn-sm btn-warning"
                                       data-bs-toggle="tooltip"
                                       data-bs-title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="delete_portfolio.php?id=<?= $item['id'] ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Yakin ingin menghapus portofolio ini?')"
                                       data-bs-toggle="tooltip"
                                       data-bs-title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
</body>
</html>