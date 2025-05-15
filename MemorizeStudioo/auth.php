<?php
session_start();

// Fungsi untuk mengecek apakah user sudah login
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}

function requireAdmin() {
    if (!isAdmin()) {
        header("Location: index.php");
        exit;
    }
}

// Mendapatkan nama pengguna yang sedang login
function getUserName() {
    return $_SESSION['name'] ?? 'User';
}

// Redirect ke halaman login jika belum login
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

// Redirect ke halaman utama jika sudah login
function requireLogout() {
    if (isLoggedIn()) {
        header("Location: index.php");
        exit;
    }
}

// Untuk kompatibilitas sementara (jika ada kode yang masih pakai struktur lama)
if (!isset($_SESSION['user']) && isset($_SESSION['user_id'])) {
    $_SESSION['user'] = [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['name'] ?? '',
        'email' => $_SESSION['user_email'] ?? '',
        'is_admin' => ($_SESSION['role'] ?? '') === 'admin'
    ];
}
?>