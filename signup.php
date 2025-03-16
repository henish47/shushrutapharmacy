<?php
// Include database connection
include "./config.php";
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/PHPMailer/Exception.php';
require './PHPMailer/PHPMailer/PHPMailer.php';
require './PHPMailer/PHPMailer/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = htmlspecialchars($_POST['username']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format');</script>";
        exit;
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match');</script>";
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(50)); 
    $verified = 0;

    // Check if email already exists
    $check_stmt = $conn->prepare("SELECT * FROM sign_up WHERE email = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<script>alert('Email already registered! Try logging in.');</script>";
        exit;
    }

    // Insert user into the database
    $stmt = $conn->prepare("INSERT INTO sign_up (username, email, password, token, verified) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $username, $email, $hashed_password, $token, $verified);

    if ($stmt->execute()) {
        // Email verification link
        $verificationLink = "http://localhost/shushrutapharmacy/verify-email.php?token=" . $token;

        // Set email subject
        $subject = "Email Verification for Shushruta Pharmacy"; 

        // Email body
        $body = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Email Verification</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f9;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    max-width: 500px;
                    margin: 30px auto;
                    background: #ffffff;
                    border-radius: 10px;
                    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
                    text-align: center;
                    padding: 20px;
                }
                h2 {
                    color: #2c6e49;
                }
                p {
                    font-size: 16px;
                    color: #333;
                }
                .btn {
                    display: inline-block;
                    background-color: #2c6e49;
                    color: #ffffff;
                    text-decoration: none;
                    font-size: 18px;
                    padding: 10px 20px;
                    border-radius: 5px;
                    margin-top: 20px;
                }
                .btn:hover {
                    background-color: #25543e;
                    color: #ffffff;
                }
                .footer {
                    margin-top: 20px;
                    font-size: 14px;
                    color: #888;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>Email Verification Required</h2>
                <p>Dear ' . htmlspecialchars($username) . ',</p>
                <p>Thank you for signing up at <b>Shushruta Pharmacy</b>. Please verify your email address to activate your account.</p>
                <a href="' . $verificationLink . '" class="btn">Verify Email</a>
                <p>If you did not sign up, you can ignore this email.</p>
                <div class="footer">© ' . date("Y") . ' Shushruta Pharmacy. All rights reserved.</div>
            </div>
        </body>
        </html>';

        // Send email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'hsavaliya989@rku.ac.in'; // Replace with your email
            $mail->Password = 'ipsj iovb mkzj bydk'; // Use an app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('hsavaliya989@rku.ac.in', 'Shushruta Pharmacy');
            if (!empty($email) && !empty($username)) {
                $mail->addAddress(trim($email), trim($username));
            } else {
                echo "<script>alert('Invalid email or username');</script>";
                exit;
            }

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
            echo "<script>alert('Verification email sent. Check your inbox.');</script>";
        } catch (Exception $e) {
            echo "<script>alert('Failed to send verification email. Error: " . $mail->ErrorInfo . "');</script>";
        }
    } else {
        echo "<script>alert('Error during registration');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Sushruta Pharmacy</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="./jquery-3.7.1.min.js"></script>
    <script src="./jquery.validate.min.js"></script>
    <script src="./additional-methods.js"></script>
    <script src="./validtion.js"></script>
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
        <h3 class="text-center text-success">Sign Up</h3>
        <p class="text-center text-muted">Create your account</p>

        <form method="POST" class="needs-validation" id="myForm" novalidate>
            <div class="mb-3">
                <label class="form-label text-success">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                <span id="usernameError" class="text-danger error"></span>
            </div>

            <div class="mb-3">
                <label class="form-label text-success">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter email" required>
                <span id="emailError" class="text-danger error"></span>
            </div>

            <div class="mb-3">
                <label class="form-label text-success">Password</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
                    <span class="input-group-text" onclick="togglePassword('password', 'eye-icon1')">
                        <i class="fas fa-eye" id="eye-icon1"></i>
                    </span>
                </div>
                <span id="passwordError" class="text-danger error"></span>
            </div>

            <div class="mb-3">
                <label class="form-label text-success">Confirm Password</label>
                <div class="input-group">
                    <input type="password" id="confirm-password" name="confirm-password" class="form-control" placeholder="Confirm password" required>
                    <span class="input-group-text" onclick="togglePassword('confirm-password', 'eye-icon2')">
                        <i class="fas fa-eye" id="eye-icon2"></i>
                    </span>
                </div>
                <span id="confirmPasswordError" class="text-danger error"></span>
            </div>

            <button type="submit" class="btn btn-custom w-100">Sign Up</button>
        </form>

        <div class="text-center mt-3">
            <a href="login.php" class="text-decoration-none text-danger">Already have an account? Login here</a>
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


<?php
// config.php - Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "sushruta_pharmacy";

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
