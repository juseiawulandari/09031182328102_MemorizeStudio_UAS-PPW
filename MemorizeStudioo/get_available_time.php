<?php
require_once 'auth.php';
header('Content-Type: application/json');

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "memorize_studio");
if ($conn->connect_error) {
    die(json_encode(['error' => 'Koneksi database gagal']));
}

$date = $_GET['date'] ?? '';
if (empty($date)) {
    die(json_encode(['error' => 'Tanggal tidak valid']));
}

// Ambil jam yang sudah dipesan pada tanggal tersebut
$stmt = $conn->prepare("SELECT time FROM reservasi WHERE date = ?");
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();

$booked_times = [];
while ($row = $result->fetch_assoc()) {
    $booked_times[] = $row['time'];
}

// Generate semua slot waktu
$all_slots = [];
$start = strtotime('08:00');
$end = strtotime('20:00');
for ($i = $start; $i <= $end; $i += 1800) {
    $all_slots[] = date('H:i', $i);
}

// Filter jam yang tersedia
$available_times = array_diff($all_slots, $booked_times);

echo json_encode([
    'availableTimes' => array_values($available_times)
]);

$stmt->close();
$conn->close();
?>