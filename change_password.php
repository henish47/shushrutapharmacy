<?php
session_start();
include 'config.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $message = "<div class='alert alert-warning text-center'>New password and confirm password do not match.</div>";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $conn->query("UPDATE sign_up SET password='$hashed_password' WHERE email='$email'");
        $message = "<div class='alert alert-success text-center'>Password changed successfully!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Sushruta Pharmacy</title>
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
        <h3 class="text-center text-success">Change Password</h3>
        <p class="text-center text-muted">Update your account password</p>

        <?php echo $message; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label text-success">New Password</label>
                <div class="input-group">
                    <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Enter new password" required>
                    <span class="input-group-text" onclick="togglePassword('new_password', 'eye-icon1')">
                        <i class="fas fa-eye" id="eye-icon1"></i>
                    </span>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label text-success">Confirm New Password</label>
                <div class="input-group">
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm new password" required>
                    <span class="input-group-text" onclick="togglePassword('confirm_password', 'eye-icon2')">
                        <i class="fas fa-eye" id="eye-icon2"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn btn-custom w-100">Change Password</button>
        </form>

        <div class="text-center mt-3">
            <a href="dashboard.php" class="text-decoration-none text-danger">Back to Dashboard</a>
        </div>
    </div>
</div>

<script>
    function togglePassword(fieldId, iconId) {
        var passwordField = document.getElementById(fieldId);
        var eyeIcon = document.getElementById(iconId);
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
