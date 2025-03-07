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

<!-- hello henish -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f9;
        }

        .container {
            max-width: 400px;
            margin: 50px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 20px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #2c6e49;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
            text-align: left;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        button {
            background-color: #2c6e49;
            color: white;
            font-size: 1rem;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #25543e;
        }

        .footer {
            text-align: center;
            padding: 15px;
            background: #f4f4f4;
        }

        .footer a {
            color: #2c6e49;
            text-decoration: none;
            font-weight: bold;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>
        <form method="POST" action="">
            <label for="username">Username</label>
            <input type="text" name="username" required>

            <label for="email">Email</label>
            <input type="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" required>

            <label for="confirm-password">Confirm Password</label>
            <input type="password" name="confirm-password" required>

            <button type="submit">Sign Up</button>
        </form>
        <div class="footer">
            <a href="login.php">Already have an account? Login here</a>
        </div>
    </div>
</body>
</html>

<?php
// config.php - Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "shushruta_pharmacy";

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
