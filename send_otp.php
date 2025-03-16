<?php
session_start();
include 'config.php';
include 'mail.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);

    // Check if email exists
    $query = "SELECT * FROM sign_up WHERE email = '$email'";
    $result = $conn->query($query);

    if (!$result) {
        die("Error in SELECT query: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $otp = rand(100000, 999999);
        $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

        // Store OTP in Database
        $updateQuery = "UPDATE sign_up SET otp_code='$otp', otp_expiry='$expiry' WHERE email='$email'";

        if ($conn->query($updateQuery) === TRUE) {
            echo "✅ OTP successfully stored in DB: $otp";
        } else {
            die("❌ Error in UPDATE query: " . $conn->error);
        }

        // Send OTP via Email
        if (sendOTP($email, $otp)) {
            $_SESSION['email'] = $email;
            $_SESSION['message'] = "OTP sent to your email.";
            header("Location: verify_otp.php");
            exit();
        } else {
            $_SESSION['message'] = "Error sending OTP.";
        }
    } else {
        $_SESSION['message'] = "Email not registered.";
    }
}

header("Location: forgot_password.php");
exit();
?>
