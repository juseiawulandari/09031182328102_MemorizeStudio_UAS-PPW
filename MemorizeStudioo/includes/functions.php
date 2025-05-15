<?php
// Fungsi untuk mendapatkan semua item portofolio
function getAllPortfolioItems($conn) {
    $query = "SELECT * FROM portfolio ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Fungsi untuk mendapatkan item portofolio berdasarkan ID
function getPortfolioItemById($conn, $id) {
    $query = "SELECT * FROM portfolio WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

// Fungsi untuk menambahkan item portofolio
function addPortfolioItem($conn, $title, $description, $image_path, $package_type) {
    $query = "INSERT INTO portfolio (title, description, image_path, package_type) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $title, $description, $image_path, $package_type);
    return mysqli_stmt_execute($stmt);
}

// Fungsi untuk mengupdate item portofolio
function updatePortfolioItem($conn, $id, $title, $description, $image_path, $package_type) {
    $query = "UPDATE portfolio SET title = ?, description = ?, image_path = ?, package_type = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssi", $title, $description, $image_path, $package_type, $id);
    return mysqli_stmt_execute($stmt);
}

// Fungsi untuk menghapus item portofolio
function deletePortfolioItem($conn, $id) {
    $query = "DELETE FROM portfolio WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    return mysqli_stmt_execute($stmt);
}
?>