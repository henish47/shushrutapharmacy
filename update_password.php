<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_SESSION['email'];

    // Update password and clear OTP
    $conn->query("UPDATE users SET password='$password', otp_code=NULL, otp_expiry=NULL WHERE email='$email'");

    $_SESSION['message'] = "Password reset successfully. You can now login.";
    session_destroy();
    header("Location: login.php");
}
exit();
?>
