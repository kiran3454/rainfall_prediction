<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: var(--bs-body-bg);
            transition: all 0.3s ease;
        }
        .dashboard-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            margin-bottom: 20px;
            height: 100%;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #6a11cb;
        }
        .btn-action {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 50px;
            transition: all 0.3s;
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            color: white;
        }
        .theme-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 2rem;
        }
        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        }
        .sidebar {
            position: sticky;
            top: 20px;
        }
        @media (max-width: 768px) {
            .sidebar {
                position: static;
                margin-bottom: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Theme Toggle -->
    <button class="btn btn-sm btn-outline-secondary theme-toggle" id="themeToggle">
        <i class="fas fa-moon"></i>
    </button>

    <!-- Dashboard Header -->
    <header class="dashboard-header text-center">
        <div class="container">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <h1 class="display-5 fw-bold">Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?></h1>
            <p class="lead">Weather Data Management System</p>
        </div>
    </header>

    <div class="container">
        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-md-3">
                <div class="sidebar">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills flex-column">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#">
                                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="add_data.php">
                                        <i class="fas fa-cloud-rain me-2"></i> Add Rainfall Data
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="view_data.php">
                                        <i class="fas fa-chart-bar me-2"></i> View Data
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="profile.php">
                                        <i class="fas fa-user-cog me-2"></i> Profile
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-danger" href="logout.php">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <div class="row">
                    <!-- Quick Stats Cards -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-cloud-rain card-icon"></i>
                                <h5 class="card-title">Rainfall Data</h5>
                                <p class="card-text">Add and manage rainfall measurements</p>
                                <a href="add_data.php" class="btn btn-action">
                                    <i class="fas fa-plus me-2"></i>Add Data
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-chart-line card-icon"></i>
                                <h5 class="card-title">Data Analysis</h5>
                                <p class="card-text">View and analyze historical data</p>
                                <a href="view_data.php" class="btn btn-action">
                                    <i class="fas fa-chart-bar me-2"></i>View Charts
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity Section -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-history me-2"></i>Recent Activity</h5>
                                <div class="list-group">
                                    <a href="#" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">New rainfall data added</h6>
                                            <small>2 hours ago</small>
                                        </div>
                                        <p class="mb-1">25mm recorded in Central District</p>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">System update</h6>
                                            <small>1 day ago</small>
                                        </div>
                                        <p class="mb-1">New features added to dashboard</p>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Your last login</h6>
                                            <small>3 days ago</small>
                                        </div>
                                        <p class="mb-1">Logged in from 192.168.1.1</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Theme toggle
        document.getElementById('themeToggle').addEventListener('click', function() {
            const html = document.documentElement;
            const icon = this.querySelector('i');
            
            if (html.getAttribute('data-bs-theme') === 'dark') {
                html.setAttribute('data-bs-theme', 'light');
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            } else {
                html.setAttribute('data-bs-theme', 'dark');
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            }
        });

        // Check for preferred color scheme
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
            document.getElementById('themeToggle').querySelector('i').classList.remove('fa-moon');
            document.getElementById('themeToggle').querySelector('i').classList.add('fa-sun');
        }

        // Activate tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>
</html>