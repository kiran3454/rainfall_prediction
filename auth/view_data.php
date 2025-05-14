<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$sql = "SELECT * FROM rainfall_data WHERE user_id='$user_id' ORDER BY date DESC";
$result = $conn->query($sql);

// Check for success message from add/edit/delete operations
$success_message = '';
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rainfall Data</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto+Condensed:wght@400;700&display=swap" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e6f2ff 0%, #ffffff 100%);
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }
        
        .rain-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }
        
        .rain {
            position: absolute;
            width: 2px;
            height: 50px;
            background: rgba(0, 119, 255, 0.3);
            animation: rain linear infinite;
        }
        
        @keyframes rain {
            0% {
                transform: translateY(-50px);
            }
            100% {
                transform: translateY(calc(100vh + 50px));
            }
        }
        
        .data-container {
            max-width: 1200px;
            margin: 30px auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 119, 255, 0.1);
            padding: 30px;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0, 119, 255, 0.1);
        }
        
        .data-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 10px;
            background: linear-gradient(90deg, #0077ff, #00a1ff, #0077ff);
            background-size: 200% 100%;
            animation: gradient 3s ease infinite;
        }
        
        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }
        
        .page-title {
            font-family: 'Roboto Condensed', sans-serif;
            color: #0077ff;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 700;
            position: relative;
        }
        
        .page-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 3px;
            background: #0077ff;
            margin: 10px auto;
            border-radius: 3px;
        }
        
        .btn-action {
            background: linear-gradient(135deg, #0077ff 0%, #00a1ff 100%);
            border: none;
            color: white;
            padding: 6px 12px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
            margin: 2px;
        }
        
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 119, 255, 0.2);
            color: white;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
        }
        
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead {
            background: linear-gradient(135deg, #0077ff 0%, #00a1ff 100%);
            color: white;
        }
        
        .table th {
            border: none;
            padding: 15px;
            font-weight: 600;
        }
        
        .table td {
            vertical-align: middle;
            padding: 12px 15px;
            border-color: #f1f1f1;
        }
        
        .table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        .table tr:hover {
            background-color: #f1f7ff;
        }
        
        .success-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .success-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            max-width: 400px;
            animation: zoomIn 0.5s;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .success-icon {
            font-size: 60px;
            color: #28a745;
            margin-bottom: 20px;
            animation: bounce 1s infinite alternate;
        }
        
        .no-data {
            text-align: center;
            padding: 50px;
            color: #6c757d;
        }
        
        .no-data i {
            font-size: 50px;
            margin-bottom: 20px;
            color: #0077ff;
        }
        
        .add-data-btn {
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .table-responsive {
                border: none;
            }
            
            .table thead {
                display: none;
            }
            
            .table, .table tbody, .table tr, .table td {
                display: block;
                width: 100%;
            }
            
            .table tr {
                margin-bottom: 15px;
                border-radius: 10px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }
            
            .table td {
                text-align: right;
                padding-left: 50%;
                position: relative;
                border-bottom: 1px solid #f1f1f1;
            }
            
            .table td::before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                width: 45%;
                padding-right: 10px;
                font-weight: 600;
                text-align: left;
                color: #0077ff;
            }
            
            .actions-cell {
                display: flex;
                justify-content: flex-end;
            }
        }
    </style>
</head>
<body>
    <!-- Rain Animation Background -->
    <div class="rain-animation" id="rainAnimation"></div>
    
    <!-- Main Data Container -->
    <div class="data-container animate__animated animate__fadeIn">
        <h2 class="page-title"><i class="fas fa-cloud-rain me-2"></i>RAINFALL DATA RECORDS</h2>
        
        <?php if($success_message): ?>
            <div class="alert alert-success alert-dismissible fade show animate__animated animate__bounceIn" role="alert">
                <?php echo $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <a href="add_data.php" class="btn btn-primary add-data-btn animate__animated animate__pulse">
            <i class="fas fa-plus-circle me-2"></i>Add New Rainfall Data
        </a>
        
        <div class="table-responsive">
            <?php if ($result->num_rows > 0): ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Rainfall (mm)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="animate__animated animate__fadeIn">
                                <td data-label="ID"><?php echo htmlspecialchars($row['id']); ?></td>
                                <td data-label="Date"><?php echo htmlspecialchars($row['date']); ?></td>
                                <td data-label="Location"><?php echo htmlspecialchars($row['location']); ?></td>
                                <td data-label="Rainfall">
                                    <span class="badge bg-primary bg-gradient">
                                        <?php echo htmlspecialchars($row['rainfall_amount']); ?> mm
                                    </span>
                                </td>
                                <td data-label="Actions" class="actions-cell">
                                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-action btn-sm">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    <a href="delete_data.php?id=<?php echo $row['id']; ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Are you sure you want to delete this record?')">
                                        <i class="fas fa-trash-alt me-1"></i>Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data animate__animated animate__fadeIn">
                    <i class="fas fa-cloud-showers-heavy"></i>
                    <h3>No Rainfall Data Found</h3>
                    <p>You haven't recorded any rainfall data yet.</p>
                    <a href="add_data.php" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>Add Your First Record
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Success Modal (for future use) -->
    <div class="success-modal" id="successModal">
        <div class="success-content">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3>Success!</h3>
            <p id="successMessage">Your operation was completed successfully.</p>
            <button class="btn btn-primary mt-3" onclick="closeSuccessModal()">Continue</button>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Create rain animation
        function createRain() {
            const rainContainer = document.getElementById('rainAnimation');
            const rainCount = 50;
            
            for (let i = 0; i < rainCount; i++) {
                const rain = document.createElement('div');
                rain.classList.add('rain');
                rain.style.left = Math.random() * 100 + 'vw';
                rain.style.animationDuration = (Math.random() * 0.5 + 0.5) + 's';
                rain.style.animationDelay = Math.random() * 2 + 's';
                rain.style.opacity = Math.random() * 0.5 + 0.3;
                rainContainer.appendChild(rain);
            }
        }
        
        // Show success modal with specific message
        function showSuccessModal(message) {
            document.getElementById('successMessage').textContent = message;
            document.getElementById('successModal').style.display = 'flex';
        }
        
        // Close success modal
        function closeSuccessModal() {
            document.getElementById('successModal').style.display = 'none';
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            createRain();
            
            // Check for success message in URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success')) {
                const message = urlParams.get('message') || 'Operation completed successfully';
                showSuccessModal(message);
                
                // Clean URL
                window.history.replaceState({}, document.title, window.location.pathname);
            }
            
            // Add animation to table rows on hover
            const tableRows = document.querySelectorAll('.table tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', () => {
                    row.classList.add('animate__animated', 'animate__pulse');
                });
                row.addEventListener('mouseleave', () => {
                    row.classList.remove('animate__animated', 'animate__pulse');
                });
            });
        });
        
    </script>
</body>
</html>