  
    <?php
    session_start();
    
    require 'config.php'; // Database connection

    $error = "";
    $success = "";

    // Handle password change
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_password = trim($_POST['new_password']);
        $confirm_password = trim($_POST['confirm_password']);

        if ($new_password !== $confirm_password) {
            $error = "Passwords do not match!";
        } else {
            $email = $_SESSION['user']['email'];
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

            // Update password in database
            $stmt = $conn->prepare("UPDATE sign_up SET password=? WHERE email=?");
            $stmt->bind_param("ss", $hashed_password, $email);

            if ($stmt->execute()) {
                $success = "Password updated successfully!";
            } else {
                $error = "Something went wrong!";
            }
        }
    }
    ?>

<?php include_once './admin_navbar.php'; include_once './sidebar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="./bootstrap.min.css">
    <script src="./bootstrap.bundle.min.js"></script>
    <title>Admin Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background: #f4f4f9; }
        .container { max-width: 500px; margin: 50px auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body>

<div class="container mt-5 p-5">
    <h3 class="text-center">Change Password</h3>
  
    <!-- Display error or success messages -->
    <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <?php if ($success) echo "<div class='alert alert-success'>$success</div>"; ?>

    <!-- Password Change Form -->
    <form action="" method="POST">
        <div class="mb-3">
            <label class="form-label">New Password</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Confirm New Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success w-100">Change Password</button>
    </form>
</div>

</body>
</html>
