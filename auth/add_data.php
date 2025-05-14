<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    $date = $_POST["date"];
    $location = $_POST["location"];
    $rainfall_amount = $_POST["rainfall_amount"];

    $sql = "INSERT INTO rainfall_data (user_id, date, location, rainfall_amount) 
            VALUES ('$user_id', '$date', '$location', '$rainfall_amount')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_message'] = "Rainfall data added successfully!";
        header("Location: view_data.php");
        exit();
    } else {
        $error_message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Rainfall Data</title>
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
        
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 119, 255, 0.1);
            padding: 30px;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0, 119, 255, 0.1);
        }
        
        .form-container::before {
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
        
        .form-title {
            font-family: 'Roboto Condensed', sans-serif;
            color: #0077ff;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 700;
            position: relative;
        }
        
        .form-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 3px;
            background: #0077ff;
            margin: 10px auto;
            border-radius: 3px;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: #0077ff;
            box-shadow: 0 0 0 0.25rem rgba(0, 119, 255, 0.25);
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #0077ff 0%, #00a1ff 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            transition: all 0.3s;
            width: 100%;
            margin-top: 10px;
        }
        
        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 119, 255, 0.2);
        }
        
        .btn-submit:active {
            transform: translateY(-1px);
        }
        
        .input-group-text {
            background-color: #f8f9fa;
            border-color: #e0e0e0;
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
    </style>
</head>
<body>
    <!-- Rain Animation Background -->
    <div class="rain-animation" id="rainAnimation"></div>
    
    <!-- Main Form Container -->
    <div class="form-container animate__animated animate__fadeInUp">
        <h2 class="form-title"><i class="fas fa-cloud-rain me-2"></i>ADD RAINFALL DATA</h2>
        
        <?php if(isset($error_message)): ?>
            <div class="alert alert-danger animate__animated animate__shakeX">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="mb-4">
                <label for="date" class="form-label">Date</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="location" class="form-label">Location</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    <input type="text" class="form-control" id="location" name="location" placeholder="Enter location" required>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="rainfall_amount" class="form-label">Rainfall Amount (mm)</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-tint"></i></span>
                    <input type="number" step="0.01" class="form-control" id="rainfall_amount" name="rainfall_amount" placeholder="Enter amount in millimeters" required>
                    <span class="input-group-text">mm</span>
                </div>
            </div>
            
            <button type="submit" class="btn btn-submit animate__animated animate__pulse animate__infinite animate__slower">
                <i class="fas fa-plus-circle me-2"></i>ADD DATA
            </button>
        </form>
    </div>
    
    <!-- Success Modal -->
    <div class="success-modal" id="successModal">
        <div class="success-content">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3>Success!</h3>
            <p>Your rainfall data has been successfully recorded.</p>
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
        
        // Show success modal if redirected from successful submission
        function checkSuccessMessage() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success')) {
                document.getElementById('successModal').style.display = 'flex';
            }
        }
        
        // Close success modal
        function closeSuccessModal() {
            document.getElementById('successModal').style.display = 'none';
            // Remove the success parameter from URL
            window.history.replaceState({}, document.title, window.location.pathname);
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            createRain();
            checkSuccessMessage();
            
            // Form submission animation
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const btn = document.querySelector('.btn-submit');
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
                    btn.disabled = true;
                });
            }
        });
    </script>
</body>
</html>