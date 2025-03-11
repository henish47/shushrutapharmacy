<?php
session_start();
require_once "config.php"; // Ensure PDO connection is included

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);

    // Check if the email exists in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate reset token
        $resetToken = bin2hex(random_bytes(50)); // Secure token
        $expiryTime = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Store the token in the database
        $updateStmt = $pdo->prepare("UPDATE users SET reset_token = ?, token_expiry = ? WHERE email = ?");
        $updateStmt->execute([$resetToken, $expiryTime, $email]);

        // Create password reset link
        $baseUrl = "http://localhost/shushrutapharmacy"; // Change for production
        $resetLink = $baseUrl . "/reset_password.php?token=" . urlencode($resetToken);
        
        $_SESSION['success_message'] = "A password reset link has been sent to your email.";

        // Simulate sending email (Use PHPMailer in production)
        // mail($email, "Password Reset", "Click the link to reset: $resetLink");
    } else {
        $_SESSION['error_message'] = "No account found with that email.";
    }

    header("Location: forgot_password.php");
    exit();
}
?>
