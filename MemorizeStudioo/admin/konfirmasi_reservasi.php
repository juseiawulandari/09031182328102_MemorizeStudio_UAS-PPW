<?php
require_once '../auth.php';

// Cek apakah user admin
if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
    header("Location: ../login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "memorize_studio");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses konfirmasi jika ada parameter id
if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    
    // Update status reservasi
    $sql = "UPDATE reservasi SET status = 'confirmed' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Reservasi berhasil dikonfirmasi!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Gagal mengkonfirmasi reservasi: " . $stmt->error;
        $_SESSION['message_type'] = "danger";
    }
    
    $stmt->close();
    header("Location: reservasi.php");
    exit;
} else {
    header("Location: reservasi.php");
    exit;
}
?>