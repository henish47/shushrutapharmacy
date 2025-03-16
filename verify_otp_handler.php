<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp = $conn->real_escape_string($_POST['otp']);
    $email = $_SESSION['email'];

    $query = "SELECT * FROM users WHERE email='$email' AND otp_code='$otp' AND otp_expiry > NOW()";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $_SESSION['otp_verified'] = true;
        header("Location: reset_password.php");
    } else {
        $_SESSION['message'] = "Invalid or expired OTP.";
        header("Location: verify_otp.php");
    }
}
exit();
?>
