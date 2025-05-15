<?php
require_once '../../auth.php';
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!isset($_GET['id'])) {
    header('Location: list_portfolio.php');
    exit;
}

$id = $_GET['id'];
$error = '';
$success = '';

// Ambil data portofolio yang akan diedit
$portfolio = getPortfolioItemById($conn, $id);

if (!$portfolio) {
    header('Location: list_portfolio.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $package_type = $_POST['package_type'] ?? '';
    $current_image = $portfolio['image_path'];
    
    // Handle file upload jika ada gambar baru
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "../../assets/images/portfolio/";
        $image_name = uniqid() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            $error = "Hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
        } elseif ($_FILES["image"]["size"] > 5000000) { // 5MB
            $error = "Ukuran file terlalu besar (maksimal 5MB).";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $current_image = "assets/images/portfolio/" . $image_name;
                // Hapus gambar lama jika ada
                if (file_exists("../../" . $portfolio['image_path'])) {
                    unlink("../../" . $portfolio['image_path']);
                }
            } else {
                $error = "Terjadi kesalahan saat mengupload gambar.";
            }
        }
    }
    
    if (empty($error)) {
        if (updatePortfolioItem($conn, $id, $title, $description, $current_image, $package_type)) {
            $success = "Portofolio berhasil diperbarui!";
            header("Location: list_portfolio.php?success=" . urlencode($success));
            exit;
        } else {
            $error = "Gagal memperbarui portofolio: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Portofolio - Memorize Studio</title>
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
        
        /* Card Styles */
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: var(--soft-shadow);
            margin-bottom: 2rem;
            border: 1px solid rgba(215, 204, 200, 0.3);
            transition: all 0.3s ease;
        }
        
        .card-header {
            background-color: var(--pale-brown);
            border-bottom: 1px solid var(--light-brown);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
        }
        
        /* Form Styles */
        .form-control, .form-select, .form-file {
            border: 1px solid var(--light-brown);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--medium-brown);
            box-shadow: 0 0 0 0.25rem rgba(141, 110, 99, 0.25);
        }
        
        .form-text {
            color: var(--medium-brown);
            font-size: 0.875rem;
        }
        
        /* Button Styles */
        .btn {
            font-weight: 500;
            padding: 0.75rem 1.5rem;
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
        
        /* Alert Styles */
        .alert {
            border-radius: 0.5rem;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        /* Image Preview */
        .current-image {
            max-width: 300px;
            max-height: 200px;
            border-radius: 8px;
            border: 1px solid var(--light-brown);
            margin-bottom: 1rem;
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
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .current-image {
                max-width: 100%;
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
                <h1>Edit Portofolio</h1>
                <div class="d-flex gap-2">
                    <a href="list_portfolio.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            
            <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Form Edit Portofolio</h5>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label for="title" class="form-label">Judul Portofolio</label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="<?= htmlspecialchars($portfolio['title']) ?>" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="5" required><?= 
                                htmlspecialchars($portfolio['description']) 
                            ?></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label for="package_type" class="form-label">Jenis Paket</label>
                            <select class="form-select" id="package_type" name="package_type" required>
                                <option value="Basic" <?= $portfolio['package_type'] === 'Basic' ? 'selected' : '' ?>>Basic</option>
                                <option value="Standard" <?= $portfolio['package_type'] === 'Standard' ? 'selected' : '' ?>>Standard</option>
                                <option value="Premium" <?= $portfolio['package_type'] === 'Premium' ? 'selected' : '' ?>>Premium</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Gambar Saat Ini</label>
                            <div>
                                <img src="../../<?= htmlspecialchars($portfolio['image_path']) ?>" 
                                     alt="Current Image" 
                                     class="current-image">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="image" class="form-label">Ganti Gambar (biarkan kosong jika tidak ingin mengubah)</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <div class="form-text">Format: JPG, JPEG, PNG, GIF (maksimal 5MB)</div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>