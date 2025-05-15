<?php
require_once '../auth.php';

// Check if user is admin
if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
    header("Location: ../login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "memorize_studio");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process deletion if id parameter exists
if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    
    // If confirmed
    if (isset($_GET['confirm']) && $_GET['confirm'] == 'yes') {
        $sql = "DELETE FROM reservasi WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Reservation deleted successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Failed to delete reservation: " . $stmt->error;
            $_SESSION['message_type'] = "danger";
        }
        
        $stmt->close();
        header("Location: reservasi.php");
        exit;
    }
    
    // Get reservation data for confirmation
    $sql = "SELECT * FROM reservasi WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $_SESSION['message'] = "Reservation not found!";
        $_SESSION['message_type'] = "danger";
        header("Location: reservasi.php");
        exit;
    }
    
    $reservasi = $result->fetch_assoc();
    $stmt->close();
} else {
    header("Location: reservasi.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Reservation - Memorize Studio</title>
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
        
        /* Main Content Styles */
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            width: calc(100% - 250px);
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
        
        /* Confirmation Card */
        .confirmation-card {
            width: 100%;
            margin: 0;
        }
        
        /* Button Styles */
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        
        .btn-danger:hover {
            background-color: #bb2d3b;
            border-color: #b02a37;
        }
        
        .btn-secondary {
            background-color: var(--medium-brown);
            border-color: var(--medium-brown);
        }
        
        .btn-secondary:hover {
            background-color: var(--dark-brown);
            border-color: var(--dark-brown);
        }
        
        /* Reservation Details */
        .reservation-details {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .detail-item {
            background-color: var(--pale-brown);
            padding: 1rem;
            border-radius: 0.5rem;
        }
        
        .detail-label {
            font-weight: 600;
            color: var(--medium-brown);
            margin-bottom: 0.25rem;
        }
        
        .detail-value {
            font-size: 1.1rem;
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .sidebar {
                width: 220px;
            }
            .main-content {
                margin-left: 220px;
                width: calc(100% - 220px);
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
                width: 100%;
            }
            
            .reservation-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar Navigation -->
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
                    <a class="nav-link active" href="reservasi.php">
                        <i class="bi bi-calendar-check"></i> Reservations
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="users.php">
                        <i class="bi bi-people"></i> Users
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

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
            <h1 class="h2">Delete Reservation</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="reservasi.php" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <!-- Confirmation Card -->
        <div class="card confirmation-card">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i> Confirm Deletion</h4>
            </div>
            <div class="card-body">
                <p class="lead mb-4">Are you sure you want to delete this reservation?</p>
                
                <!-- Reservation Details -->
                <div class="reservation-details">
                    <div class="detail-item">
                        <div class="detail-label">Name</div>
                        <div class="detail-value"><?= htmlspecialchars($reservasi['name']) ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Email</div>
                        <div class="detail-value"><?= htmlspecialchars($reservasi['email']) ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Package</div>
                        <div class="detail-value"><?= htmlspecialchars($reservasi['package']) ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Date</div>
                        <div class="detail-value"><?= date('d/m/Y', strtotime($reservasi['date'])) ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Time</div>
                        <div class="detail-value"><?= date('H:i', strtotime($reservasi['time'])) ?></div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="reservasi.php" class="btn btn-secondary px-4 py-2">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                    <a href="hapus_reservasi.php?id=<?= $reservasi['id'] ?>&confirm=yes" class="btn btn-danger px-4 py-2">
                        <i class="bi bi-trash me-1"></i> Yes, Delete
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>