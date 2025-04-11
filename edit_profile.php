<?php
session_start();
require_once "config.php"; // Database connection

// ✅ Ensure user is logged in
if (!isset($_SESSION['user']['id'])) {
    die("Error: User not logged in.");
}

// Get user ID from session
$userId = $_SESSION['user']['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ✅ Trim Inputs
    $newUsername = trim($_POST['username'] ?? '');
    $newEmail = trim($_POST['email'] ?? '');
    
    // ✅ Profile Picture Upload Handling
    $uploadDir = "uploads/";
    if (!file_exists($uploadDir)) { 
        mkdir($uploadDir, 0777, true);
    }

    if (!empty($_FILES['profile_pic']['name'])) {
        $fileName = time() . "_" . basename($_FILES["profile_pic"]["name"]);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFile)) {
            $profilePic = $targetFile;
        } else {
            $profilePic = $_SESSION['user']['profile_pic'] ?? 'assets/profile.jpg';
        }
    } else {
        $profilePic = $_SESSION['user']['profile_pic'] ?? 'assets/profile.jpg';
    }

    // ✅ Update Database
    $stmt = $conn->prepare("UPDATE sign_up SET username=?, email=?, profile_pic=? WHERE id=?");
    if (!$stmt) {
        die("Error preparing SQL: " . $conn->error);
    }

    $stmt->bind_param("sssi", $newUsername, $newEmail, $profilePic, $userId);
    if (!$stmt->execute()) {
        die("Error executing SQL: " . $stmt->error);
    }

    $_SESSION['toast_message'] = "Profile updated successfully!";

    // ✅ Update Session Variables
    $_SESSION['user']['username'] = $newUsername;
    $_SESSION['user']['email'] = $newEmail;
    $_SESSION['user']['profile_pic'] = $profilePic;

    $stmt->close();
    $conn->close();

    // Redirect
    header("Location: edit_profile.php");
    exit();
}

// ✅ Fetch Current User Data from Session
$username = $_SESSION['user']['username'] ?? '';
$email = $_SESSION['user']['email'] ?? '';
$profilePic = $_SESSION['user']['profile_pic'] ?? 'assets/profile.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - E-commerce</title>
    <script src="./jquery-3.7.1.min.js"></script>
    <script src="./jquery.validate.min.js"></script>
    <script src="./additional-methods.js"></script>
    <script src="./validtion.js"></script>
    <link rel="stylesheet" href="./bootstrap.min.css">
    <script src="./bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>

<?php include './navbar.php'; ?>

<div class="container mt-5 card shadow-md">
    <h1 class="text-center text-success mt-3">Edit Profile</h1>

    <!-- Toast Notification -->
    <?php if (!empty($_SESSION['toast_message'])) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['toast_message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['toast_message']); ?>
    <?php endif; ?>

    <form action="edit_profile.php" method="POST" enctype="multipart/form-data" novalidate>
        <!-- Profile Picture Preview -->
        <div class="mb-3 text-center">
            <img src="<?= $profilePic ?>" alt="Profile Picture" class="rounded-circle" width="120">
        </div>

        <div class="mb-3">
            <label for="profile_pic" class="form-label">Change Profile Picture:</label>
            <input type="file" class="form-control" id="profile_pic" name="profile_pic" data-validation="file">
            <span id="profile_picError" class="invalid-feedback"></span>
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" id="username" name="username" value="<?= $username ?>" required data-validation="required alpha">
            <span id="usernameError" class="invalid-feedback"></span>

        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= $email ?>" required data-validation="required email">
            <span id="emailError" class="invalid-feedback"></span>
        </div>

        <button type="submit" class="btn btn-success mb-3 ">Save Changes</button>
        <a href="index.php" class="btn btn-secondary mb-3">Back to Home</a>
    </form>
</div>

<br>



<?php include_once "./footer.php" ?>
</body>
</html>