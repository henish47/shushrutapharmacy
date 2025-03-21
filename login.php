<?php 
session_start();
require 'config.php'; // Database connection

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if user exists in the database
    $stmt = $conn->prepare("SELECT * FROM sign_up WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Check if password matches
        if (password_verify($password, $user['password'])) {
            if ($user['verified'] == 1) {
                // Store user session
                $_SESSION['logged_in'] = true;
                $_SESSION['user'] = [
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'profile_pic' => $user['profile_pic'] ?? 'assets/profile.jpg'
                ];

                // Redirect based on role
                if ($user['role'] == 'admin') {
                    header("Location: admin.php");
                } else {
                    header("Location: index.php");
                }
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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

        <form method="post">
            <div class="mb-3">
                <label class="form-label text-success">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label class="form-label text-success">Password</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
                    <span class="input-group-text" onclick="togglePassword()">
                        <i class="fas fa-eye" id="eye-icon"></i>
                    </span>
                </div>
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
</script>

</body>
</html>
