<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Retrieve and clear toast message from session
$toastMessage = isset($_SESSION['toast_message']) ? $_SESSION['toast_message'] : "";
unset($_SESSION['toast_message']); // Remove message after displaying

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = $_POST['username'];
    $newEmail = $_POST['email'];
    $newCountry = $_POST['country'];
    $newPhone = $_POST['phone'];
    $newCity = $_POST['city'];
    $newState = $_POST['state'];
    $newZip = $_POST['zip'];
    $newAddress = $_POST['address'];

    // Handle profile picture upload
    if (!empty($_FILES['profile_pic']['name'])) {
        $uploadDir = "uploads/";
        $fileName = time() . "_" . basename($_FILES["profile_pic"]["name"]); // Unique file name
        $targetFile = $uploadDir . $fileName;

        // Ensure the uploads directory exists
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move uploaded file
        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFile)) {
            $_SESSION['user']['profile_pic'] = $targetFile; // Update session with new image path
        }
    }

    // Update session with new details
    $_SESSION['user']['username'] = $newUsername;
    $_SESSION['user']['email'] = $newEmail;
    $_SESSION['user']['country'] = $newCountry;
    $_SESSION['user']['phone'] = $newPhone;
    $_SESSION['user']['city'] = $newCity;
    $_SESSION['user']['state'] = $newState;
    $_SESSION['user']['zip'] = $newZip;
    $_SESSION['user']['address'] = $newAddress;

    // Store toast message in session
    $_SESSION['toast_message'] = "Profile updated successfully!";
    
    // Redirect to profile page
    header("Location: edit_profile.php");
    exit();
}

// Fetch current user data from session
$username = $_SESSION['user']['username'];
$email = $_SESSION['user']['email'];
$profilePic = $_SESSION['user']['profile_pic'] ?? 'assets/profile.jpg'; // Default image
$country = $_SESSION['user']['country'] ?? '';
$phone = $_SESSION['user']['phone'] ?? '';
$city = $_SESSION['user']['city'] ?? '';
$state = $_SESSION['user']['state'] ?? '';
$zip = $_SESSION['user']['zip'] ?? '';
$address = $_SESSION['user']['address'] ?? '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - E-commerce</title>
    <link rel="stylesheet" href="./bootstrap.min.css">
   <link rel="stylesheet" href="./styles/edit_profile.css">
</head>
<body>

<?php include_once './navbar.php' ?>

<div class="container mt-5">
    <h2>Edit Profile</h2>

    <!-- Toast Notification -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body"> <?= $toastMessage ?> </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <form action="edit_profile.php" method="POST" enctype="multipart/form-data">
        <!-- Profile Picture Preview -->
        <div class="mb-3 text-center">
            <img src="<?= $profilePic ?>" alt="Profile Picture" class="rounded-circle" width="120">
        </div>

        <div class="mb-3">
            <label for="profile_pic" class="form-label">Change Profile Picture:</label>
            <input type="file" class="form-control" id="profile_pic" name="profile_pic">
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" id="username" name="username" value="<?= $username ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= $email ?>" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone Number:</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?= $phone ?>">
        </div>

        <div class="mb-3">
            <label for="city" class="form-label">City:</label>
            <input type="text" class="form-control" id="city" name="city" value="<?= $city ?>">
        </div>

        <div class="mb-3">
            <label for="state" class="form-label">State:</label>
            <input type="text" class="form-control" id="state" name="state" value="<?= $state ?>">
        </div>

        <div class="mb-3">
            <label for="zip" class="form-label">Zip Code:</label>
            <input type="text" class="form-control" id="zip" name="zip" value="<?= $zip ?>">
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address:</label>
            <textarea class="form-control" id="address" name="address" rows="3"><?= $address ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="index.php" class="btn btn-secondary">Back to Home</a>
    </form>
</div>

 <br>
 
<?php include_once "./footer.php"?>
<script src="./bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let toastMessage = "<?= $toastMessage ?>";
        if (toastMessage.trim() !== "") {
            let toastEl = document.getElementById('liveToast');
            let toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    });
</script>
</body>
</html>
