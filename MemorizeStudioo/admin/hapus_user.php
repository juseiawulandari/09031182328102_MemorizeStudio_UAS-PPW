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

// Check if ID parameter exists
if (!isset($_GET['id'])) {
    $_SESSION['message'] = "ID user tidak valid";
    $_SESSION['message_type'] = 'danger';
    header("Location: users.php");
    exit;
}

$user_id = $_GET['id'];

// Start transaction
$conn->begin_transaction();

try {
    // First, delete all reservations by this user
    $delete_reservations = $conn->prepare("DELETE FROM reservasi WHERE email = (SELECT email FROM users WHERE id = ?)");
    $delete_reservations->bind_param("i", $user_id);
    $delete_reservations->execute();
    
    // Then delete the user
    $delete_user = $conn->prepare("DELETE FROM users WHERE id = ?");
    $delete_user->bind_param("i", $user_id);
    $delete_user->execute();
    
    // Check if user was actually deleted
    if ($delete_user->affected_rows > 0) {
        $conn->commit();
        $_SESSION['message'] = "User berhasil dihapus beserta semua reservasinya";
        $_SESSION['message_type'] = 'success';
    } else {
        $conn->rollback();
        $_SESSION['message'] = "User tidak ditemukan";
        $_SESSION['message_type'] = 'danger';
    }
    
    $delete_reservations->close();
    $delete_user->close();
} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['message'] = "Terjadi kesalahan: " . $e->getMessage();
    $_SESSION['message_type'] = 'danger';
}

$conn->close();

// Redirect back to users page
header("Location: users.php");
exit;
?>