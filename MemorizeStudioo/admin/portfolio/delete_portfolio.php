<?php
require_once '../../auth.php';
require_once '../../config/database.php';
require_once '../../includes/functions.php';


if (!isset($_GET['id'])) {
    header('Location: list_portfolio.php');
    exit;
}

$id = $_GET['id'];

// Ambil data portofolio untuk menghapus gambarnya
$portfolio = getPortfolioItemById($conn, $id);

if ($portfolio) {
    // Hapus gambar dari server
    if (file_exists("../../" . $portfolio['image_path'])) {
        unlink("../../" . $portfolio['image_path']);
    }
    
    // Hapus dari database
    if (deletePortfolioItem($conn, $id)) {
        $success = "Portofolio berhasil dihapus!";
        header("Location: list_portfolio.php?success=" . urlencode($success));
    } else {
        $error = "Gagal menghapus portofolio: " . mysqli_error($conn);
        header("Location: list_portfolio.php?error=" . urlencode($error));
    }
} else {
    header('Location: list_portfolio.php');
}
exit;
?>