<?php
session_start();
require 'config.php'; // Database connection

// Redirect if not logged in or not admin
if (!isset($_SESSION['logged_in']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['user']['email'];

// Fetch admin details
$stmt = $conn->prepare("SELECT username, email, profile_pic FROM sign_up WHERE email = ?");
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
    // Check if a new profile image is uploaded
    if (!empty($_FILES['profile_pic']['name'])) {
        $img_name = time() . '_' . basename($_FILES['profile_pic']['name']); // Unique filename
        $img_tmp = $_FILES['profile_pic']['tmp_name'];
        $upload_dir = "assets/uploads/"; // Add trailing slash

        // Ensure the directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Move uploaded file
        if (move_uploaded_file($img_tmp, $upload_dir . $img_name)) {
            $profile_pic = $upload_dir . $img_name;
        } else {
            $error = "Failed to upload profile image.";
            $profile_pic = $admin['profile_pic']; // Keep old image if upload fails
        }
    } else {
        $profile_pic = $admin['profile_pic']; // Keep old image if not updated
    }

    $stmt = $conn->prepare("UPDATE sign_up SET username=?, email=?, profile_pic=? WHERE email=?");
    $stmt->bind_param("ssss", $new_username, $new_email, $profile_pic, $email);

    if ($stmt->execute()) {
        $success = "Profile updated successfully!";
        $_SESSION['user']['username'] = $new_username;
        $_SESSION['user']['email'] = $new_email;
        $_SESSION['user']['profile_pic'] = $profile_pic; // Update session data
    } else {
        $error = "Something went wrong!";
    }
}

// Set default image if no profile image is found
$profile_img = !empty($admin['profile_pic']) ? $admin['profile_pic'] : "assets/people.png";
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
        .profile-pic { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; display: block; margin: 0 auto 10px; }
    </style>
</head>
<body>

<div class="container mt-5 p-5">
    <h3 class="text-center">Admin Profile</h3>
    <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <?php if ($success) echo "<div class='alert alert-success'>$success</div>"; ?>

    <!-- Profile Image -->
    <img src="<?= htmlspecialchars($profile_img); ?>" alt="Profile Picture" class="profile-pic">

    <form action="" method="POST" enctype="multipart/form-data">
         <!-- Profile Image Upload -->
         <div class="mb-3">
            <label class="form-label">Profile Image</label>
            <input type="file" name="profile_pic" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($admin['username']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($admin['email']); ?>" required>
        </div>

        <button type="submit" class="btn btn-success w-100">Update Profile</button>
        <a href="logout.php" class="btn btn-danger w-100 mt-2">Logout</a>
    </form>
</div>

</body>
</html>
