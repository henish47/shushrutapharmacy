<?php
session_start();
require 'config.php'; // Database connection

// Redirect if not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['user']['email'];

// Fetch admin details
$stmt = $conn->prepare("SELECT username, email FROM sign_up WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

$error = "";
$success = "";

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = trim($_POST['username']);
    $new_email = trim($_POST['email']);
    
    // Handle password update
    if (!empty($_POST['password'])) {
        $new_password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE sign_up SET username=?, email=?, password=? WHERE email=?");
        $stmt->bind_param("ssss", $new_username, $new_email, $new_password, $email);
    } else {
        $stmt = $conn->prepare("UPDATE sign_up SET username=?, email=? WHERE email=?");
        $stmt->bind_param("sss", $new_username, $new_email, $email);
    }

    if ($stmt->execute()) {
        $success = "Profile updated successfully!";
        $_SESSION['user']['username'] = $new_username;
        $_SESSION['user']['email'] = $new_email;
    } else {
        $error = "Something went wrong!";
    }
}
?>

<?php include_once './admin_navbar.php';
    include_once './sidebar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background: #f4f4f9; }
        .container { max-width: 500px; margin: 50px auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        .profile-pic { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; }
       
    </style>
</head>
<body>


<div class="container mt-5 p-5">
    <h3 class="text-center">Admin Profile</h3>

    <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <?php if ($success) echo "<div class='alert alert-success'>$success</div>"; ?>

  

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" value="<?= $admin['username']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= $admin['email']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">New Password (optional)</label>
            <input type="password" name="password" class="form-control">
        </div>

        

        <button type="submit" class="btn btn-success w-100">Update Profile</button>
        <a href="logout.php" class="btn btn-danger w-100 mt-2">Logout</a>
    </form>
</div>

</body>
</html>
