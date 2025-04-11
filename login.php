<?php
session_start();
require 'config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Prepare statement to prevent SQL Injection
    $stmt = $conn->prepare("SELECT * FROM sign_up WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            if ($user['verified'] == 1) {
                
                $_SESSION['logged_in'] = true;
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'profile_pic' => $user['profile_pic'] ?? 'assets/profile.jpg'
                ];

                // Fetch saved cart from database
                $cart_stmt = $conn->prepare("SELECT c.product_id, c.quantity, p.name, p.price, p.image 
                                             FROM cart c
                                             JOIN all_data_products p ON c.product_id = p.id
                                             WHERE c.user_id = ?");
                $cart_stmt->bind_param("i", $user['id']);
                $cart_stmt->execute();
                $cart_result = $cart_stmt->get_result();

                $_SESSION['cart'] = [];
                while ($cart_item = $cart_result->fetch_assoc()) {
                    $_SESSION['cart'][] = [
                        "id" => $cart_item['product_id'],
                        "name" => $cart_item['name'],
                        "price" => $cart_item['price'],
                        "image" => 'assets/uploads/' . $cart_item['image'],
                        "quantity" => $cart_item['quantity']
                    ];
                }

                // Redirect user to dashboard
                header("Location: " . ($user['role'] == 'admin' ? "admin.php" : "index.php"));
                exit();
            } else {
                $error = "Your email is not verified. Please check your inbox.";
            }
        } else {
            $error = "Invalid email or password!";
        }
    } else {
        $error = "No user found with this email!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sushruta Pharmacy</title>
    <link rel="stylesheet" href="./bootstrap.min.css">
    <script src="./bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./jquery/fontawesome.css">
    <style>
        body {
            background-color: #f0f8f0;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 128, 0, 0.2);
        }
        .btn-custom {
            background-color: #28a745;
            color: white;
            border-radius: 25px;
            font-weight: bold;
            transition: 0.3s ease-in-out;
        }
        .btn-custom:hover {
            background-color: #218838;
        }
        .form-control {
            border-radius: 10px;
            border: 1px solid #28a745;
        }
        .form-control:focus {
            border-color: #1e7e34;
            box-shadow: 0 0 5px rgba(40, 167, 69, 0.5);
        }
        .input-group-text {
            background: white;
            border: 1px solid #28a745;
            cursor: pointer;
        }
        .input-group-text i {
            color: #28a745;
        }
        .error-message {
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }
        .is-invalid {
            border-color: #dc3545 !important;
        }
        .is-valid {
            border-color: #28a745 !important;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4" style="width: 400px;">
        <h3 class="text-center text-success">Login</h3>
        <p class="text-center text-muted">Enter your credentials to continue</p>

        <?php if (!empty($error)) { ?>
            <div class="alert alert-danger text-center">
                <?php echo $error; ?>
            </div>
        <?php } ?>

        <form method="post" id="loginForm" novalidate>
            <div class="mb-3">
                <label class="form-label text-success">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
                <div class="error-message" id="emailError"></div>
            </div>
            <div class="mb-3">
                <label class="form-label text-success">Password</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required minlength="6">
                    <span class="input-group-text" onclick="togglePassword()">
                        <i class="fas fa-eye" id="eye-icon"></i>
                    </span>
                </div>
                <div class="error-message" id="passwordError"></div>
            </div>
            <button type="submit" class="btn btn-custom w-100">Login</button>
        </form>
        
        <div class="text-center mt-3">
            <p>Don't have an account? <a href="signup.php" class="text-decoration-none text-success">Sign Up</a></p>
            <p><a href="forgot_password.php" class="text-decoration-none text-danger">Forgot Password?</a></p>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        var passwordField = document.getElementById("password");
        var eyeIcon = document.getElementById("eye-icon");
        if (passwordField.type === "password") {
            passwordField.type = "text";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        } else {
            passwordField.type = "password";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('loginForm');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('passwordError');

        // Validate email format
        function validateEmail(email) {
            const re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            return re.test(String(email).toLowerCase());
        }

        // Validate form on submit
        form.addEventListener('submit', function(event) {
            let isValid = true;
            
            // Reset errors
            emailError.textContent = '';
            passwordError.textContent = '';
            emailInput.classList.remove('is-invalid', 'is-valid');
            passwordInput.classList.remove('is-invalid', 'is-valid');

            // Validate email
            if (!emailInput.value.trim()) {
                emailError.textContent = 'Email is required';
                emailInput.classList.add('is-invalid');
                isValid = false;
            } else if (!validateEmail(emailInput.value.trim())) {
                emailError.textContent = 'Please enter a valid email address';
                emailInput.classList.add('is-invalid');
                isValid = false;
            } else {
                emailInput.classList.add('is-valid');
            }

            // Validate password
            if (!passwordInput.value) {
                passwordError.textContent = 'Password is required';
                passwordInput.classList.add('is-invalid');
                isValid = false;
            } else if (passwordInput.value.length < 6) {
                passwordError.textContent = 'Password must be at least 6 characters';
                passwordInput.classList.add('is-invalid');
                isValid = false;
            } else {
                passwordInput.classList.add('is-valid');
            }

            if (!isValid) {
                event.preventDefault();
            }
        });

        // Real-time validation for email
        emailInput.addEventListener('input', function() {
            if (!this.value.trim()) {
                emailError.textContent = 'Email is required';
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else if (!validateEmail(this.value.trim())) {
                emailError.textContent = 'Please enter a valid email address';
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                emailError.textContent = '';
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });

        // Real-time validation for password
        passwordInput.addEventListener('input', function() {
            if (!this.value) {
                passwordError.textContent = 'Password is required';
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else if (this.value.length < 6) {
                passwordError.textContent = 'Password must be at least 6 characters';
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                passwordError.textContent = '';
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    });
</script>

</body>
</html>