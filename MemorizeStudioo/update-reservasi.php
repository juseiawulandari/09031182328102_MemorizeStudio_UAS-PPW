<?php
require_once 'auth.php';
$user_id = $_SESSION['user_id'];

// Check if user is logged in
$isLoggedIn = false;
$username = '';

if (function_exists('isLoggedIn')) {
    $isLoggedIn = isLoggedIn();
    if ($isLoggedIn && function_exists('getUserName')) {
        $username = getUserName();
    }
}

// Redirect if not logged in
if (!$isLoggedIn) {
    header("Location: login.php");
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "memorize_studio");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for edit form
$id = "";
$name = "";
$email = "";
$package = "";
$date = "";
$time = "";
$successMessage = "";
$errorMessage = "";

// Get reservation data
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $sql = "SELECT * FROM reservasi WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $email = $row['email'];
        $package = $row['package'];
        $date = $row['date'];
        $time = $row['time'];
    } else {
        $errorMessage = "Reservasi tidak ditemukan atau tidak memiliki akses!";
    }
    $stmt->close();
}

// Process update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $package = $_POST['package'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    
    // Validasi jam operasional
    $jam_reservasi = date('H:i', strtotime($time));
    $jam_buka = '08:00';
    $jam_tutup = '20:00';
    
    if ($jam_reservasi < $jam_buka || $jam_reservasi > $jam_tutup) {
        $errorMessage = "Reservasi hanya bisa antara jam 08:00-20:00";
    } 
    // Validasi 3 bulan ke depan
    else if ($date > date('Y-m-d', strtotime('+3 months'))) {
        $errorMessage = "Reservasi maksimal 3 bulan ke depan";
    }
    // Cek slot waktu
    else {
        $sql = "SELECT id FROM reservasi WHERE date = ? AND time = ? AND id != ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $date, $time, $id);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $errorMessage = "Slot waktu sudah dipesan";
        } else {
            $sql = "UPDATE reservasi SET package = ?, date = ?, time = ? WHERE id = ? AND user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssii", $package, $date, $time, $id, $user_id);
            
            if ($stmt->execute()) {
                $successMessage = "Reservasi berhasil diupdate!";
            } else {
                $errorMessage = "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}

// Get user's reservations
$sql = "SELECT * FROM reservasi WHERE user_id = ? ORDER BY date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memorize Studio - Update Reservasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --dark-brown: #5D4037;
            --medium-brown: #8D6E63;
            --light-brown: #D7CCC8;
            --pale-brown: #EFEBE9;
            --cream: #FAF9F6;
            --dark-gray: #424242;
            --medium-gray: #757575;
            --almost-black: #212121;
            --accent-color: #FFAB91;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--cream);
            color: var(--almost-black);
            line-height: 1.6;
        }
        
        .page-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 2.5rem;
            position: relative;
            padding-bottom: 1rem;
        }
        
        .page-header h2 {
            color: var(--dark-brown);
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }
        
        .page-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 25%;
            width: 50%;
            height: 3px;
            background: linear-gradient(90deg, var(--light-brown), var(--dark-brown), var(--light-brown));
            border-radius: 3px;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }
        
        .card-header {
            background-color: var(--dark-brown);
            color: var(--cream);
            font-weight: 600;
            padding: 1.25rem 1.5rem;
            border-bottom: none;
        }
        
        .card-body {
            background-color: var(--pale-brown);
            padding: 1.5rem;
        }
        
        .form-label {
            font-weight: 500;
            color: var(--dark-gray);
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid var(--light-brown);
            background-color: var(--cream);
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--dark-brown);
            box-shadow: 0 0 0 0.25rem rgba(93, 64, 55, 0.15);
        }
        
        .form-control:disabled, .form-control[readonly] {
            background-color: var(--pale-brown);
            border-color: var(--light-brown);
        }
        
        .btn {
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-primary {
            background-color: var(--dark-brown);
            border-color: var(--dark-brown);
        }
        
        .btn-primary:hover {
            background-color: var(--medium-brown);
            border-color: var(--medium-brown);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: var(--dark-gray);
            border-color: var(--dark-gray);
        }
        
        .btn-secondary:hover {
            background-color: var(--almost-black);
            border-color: var(--almost-black);
            transform: translateY(-2px);
        }
        
        .btn-warning {
            background-color: var(--light-brown);
            border-color: var(--light-brown);
            color: var(--almost-black);
        }
        
        .btn-warning:hover {
            background-color: var(--medium-brown);
            border-color: var(--medium-brown);
            color: white;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            background-color: var(--dark-brown);
            color: var(--cream);
            padding: 1rem;
            font-weight: 500;
            border-bottom: 2px solid var(--medium-brown);
        }
        
        .table td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
            border-top: 1px solid rgba(93, 64, 55, 0.1);
        }
        
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(215, 204, 200, 0.3);
        }
        
        .table-striped tbody tr:nth-of-type(even) {
            background-color: var(--pale-brown);
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(93, 64, 55, 0.1) !important;
        }
        
        .alert {
            border-radius: 8px;
            padding: 1rem 1.25rem;
            border: none;
        }
        
        .alert-success {
            background-color: rgba(139, 195, 74, 0.2);
            color: #2E7D32;
        }
        
        .alert-danger {
            background-color: rgba(239, 83, 80, 0.2);
            color: #C62828;
        }
        
        .alert-info {
            background-color: rgba(66, 165, 245, 0.2);
            color: #1565C0;
        }
        
        .badge {
            padding: 0.35em 0.65em;
            font-weight: 500;
            border-radius: 6px;
        }
        
        .badge-success {
            background-color: rgba(139, 195, 74, 0.2);
            color: #2E7D32;
        }
        
        .badge-warning {
            background-color: rgba(255, 183, 77, 0.2);
            color: #EF6C00;
        }
        
        .badge-danger {
            background-color: rgba(239, 83, 80, 0.2);
            color: #C62828;
        }
        
        @media (max-width: 768px) {
            .page-container {
                padding: 0 1rem;
            }
            
            .page-header h2 {
                font-size: 1.75rem;
            }
            
            .card-header {
                padding: 1rem;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .table-responsive {
                border-radius: 8px;
                border: 1px solid var(--light-brown);
            }
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="page-container">
    <div class="page-header">
        <h2><i class="fas fa-calendar-alt me-2"></i>Kelola Reservasi</h2>
        <p class="text-muted">Kelola dan perbarui reservasi studio Anda</p>
    </div>
    
    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo $successMessage; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo $errorMessage; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($id)): ?>
    <!-- Edit Reservation Form -->
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Reservasi</h4>
        </div>
        <div class="card-body">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($name); ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" readonly>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="package" class="form-label">Paket</label>
                        <select class="form-select" id="package" name="package" required>
                            <option value="Paket Basic" <?php echo ($package == "Paket Basic") ? "selected" : ""; ?>>Paket Basic - Rp 90.000</option>
                            <option value="Paket Standard" <?php echo ($package == "Paket Standard") ? "selected" : ""; ?>>Paket Standard - Rp 210.000</option>
                            <option value="Paket Premium" <?php echo ($package == "Paket Premium") ? "selected" : ""; ?>>Paket Premium - Rp 485.000</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="date" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="date" name="date" value="<?php echo htmlspecialchars($date); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="time" class="form-label">Jam</label>
                        <input type="time" class="form-control" id="time" name="time" value="<?php echo htmlspecialchars($time); ?>" required>
                    </div>
                    
                    <div class="col-12 mt-2">
                        <div class="d-flex gap-2">
                            <button type="submit" name="update" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                            <a href="update-reservasi.php" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Reservations Table -->
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Reservasi Anda</h4>
        </div>
        <div class="card-body">
            <?php if ($result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Paket</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while ($row = $result->fetch_assoc()): 
                                $statusClass = $row['status'] == 'confirmed' ? 'badge-success' : 
                                             ($row['status'] == 'pending' ? 'badge-warning' : 'badge-danger');
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['package']); ?></td>
                                <td><?php echo date('d M Y', strtotime($row['date'])); ?></td>
                                <td><?php echo date('H:i', strtotime($row['time'])); ?></td>
                                <td><span class="badge <?php echo $statusClass; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                                <td>
                                    <a href="update-reservasi.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>Anda belum memiliki reservasi.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Set minimum date to today for date picker
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        const dateInput = document.getElementById('date');
        if (dateInput) {
            dateInput.min = today;
            dateInput.max = new Date(new Date().setMonth(new Date().getMonth() + 3)).toISOString().split('T')[0];
        }
        
        // Auto dismiss alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
</body>
</html>