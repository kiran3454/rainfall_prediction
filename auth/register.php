<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    
    if ($conn->query($sql) === TRUE) {
        $success_message = "Registration successful! <a href='login.php' class='alert-link'>Login here</a>";
    } else {
        $error_message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: var(--bs-body-bg);
            transition: all 0.3s ease;
        }
        .registration-container {
            max-width: 500px;
            margin-top: 5vh;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .registration-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        .registration-body {
            padding: 30px;
            background-color: var(--bs-body-bg);
        }
        .form-control:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 0 0.25rem rgba(106, 17, 203, 0.25);
        }
        .btn-register {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            border: none;
            width: 100%;
            padding: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .theme-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        .input-group-text {
            background-color: var(--bs-tertiary-bg);
            border-color: var(--bs-border-color);
        }
        .password-toggle {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <button class="btn btn-sm btn-outline-secondary theme-toggle" id="themeToggle">
            <i class="fas fa-moon"></i>
        </button>
        
        <div class="registration-container animate__animated animate__fadeIn">
            <div class="registration-header">
                <h2><i class="fas fa-user-plus me-2"></i>Create Account</h2>
                <p class="mb-0">Join our community today</p>
            </div>
            
            <div class="registration-body">
                <?php if(isset($success_message)): ?>
                    <div class="alert alert-success animate__animated animate__bounceIn">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>
                
                <?php if(isset($error_message)): ?>
                    <div class="alert alert-danger animate__animated animate__shakeX">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
                            <div class="invalid-feedback">
                                Please choose a username.
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                            <div class="invalid-feedback">
                                Please provide a valid email.
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                            <span class="input-group-text password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </span>
                            <div class="invalid-feedback">
                                Please provide a password.
                            </div>
                        </div>
                        <div class="form-text">Use 8 or more characters with a mix of letters, numbers & symbols</div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-register mb-3 animate__animated animate__pulse animate__infinite animate__slower">
                        <i class="fas fa-user-plus me-2"></i>Register
                    </button>
                    
                    <div class="text-center mt-3">
                        <p>Already have an account? <a href="login.php" class="text-decoration-none">Sign in</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
        
        // Password toggle
        function togglePassword() {
            const password = document.getElementById('password')
            const icon = document.getElementById('toggleIcon')
            
            if (password.type === 'password') {
                password.type = 'text'
                icon.classList.remove('fa-eye')
                icon.classList.add('fa-eye-slash')
            } else {
                password.type = 'password'
                icon.classList.remove('fa-eye-slash')
                icon.classList.add('fa-eye')
            }
        }
        
        // Theme toggle
        document.getElementById('themeToggle').addEventListener('click', function() {
            const html = document.documentElement
            const icon = this.querySelector('i')
            
            if (html.getAttribute('data-bs-theme') === 'dark') {
                html.setAttribute('data-bs-theme', 'light')
                icon.classList.remove('fa-sun')
                icon.classList.add('fa-moon')
            } else {
                html.setAttribute('data-bs-theme', 'dark')
                icon.classList.remove('fa-moon')
                icon.classList.add('fa-sun')
            }
        })
        
        // Check for preferred color scheme
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.setAttribute('data-bs-theme', 'dark')
            document.getElementById('themeToggle').querySelector('i').classList.remove('fa-moon')
            document.getElementById('themeToggle').querySelector('i').classList.add('fa-sun')
        }
    </script>
</body>
</html>