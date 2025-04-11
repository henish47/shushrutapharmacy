<?php
session_start();
// Include database connection
include "./config.php";


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/PHPMailer/Exception.php';
require './PHPMailer/PHPMailer/PHPMailer.php';
require './PHPMailer/PHPMailer/SMTP.php';

$alert_message = ""; // Variable to store alert messages
$alert_type = ""; // Variable to store alert type (success, danger, etc.)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $alert_message = "Invalid email format";
        $alert_type = "danger";
    }

    // Check if passwords match
    elseif ($password !== $confirm_password) {
        $alert_message = "Passwords do not match";
        $alert_type = "danger";
    }

    // Hash password
    else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $token = bin2hex(random_bytes(50));
        $verified = 0;

        // Check if email already exists
        $check_stmt = $conn->prepare("SELECT * FROM sign_up WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $alert_message = "Email already registered! Try logging in.";
            $alert_type = "danger";
        }

        // Insert user into the database
        else {
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
                        // .btn:hover {
                        //     background-color: #25543e;
                        //     color: #ffffff;
                        // }
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
                        <a href="' . $verificationLink . '" class="btn text-white">Verify Email</a>
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
                        $alert_message = "Invalid email or username";
                        $alert_type = "danger";
                    }

                    $mail->isHTML(true);
                    $mail->Subject = $subject;
                    $mail->Body = $body;

                    $mail->send();
                    $alert_message = "Verification email sent. Check your inbox.";
                    $alert_type = "success";
                } catch (Exception $e) {
                    $alert_message = "Failed to send verification email. Error: " . $mail->ErrorInfo;
                    $alert_type = "danger";
                }
            } else {
                $alert_message = "Error during registration";
                $alert_type = "danger";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Sushruta Pharmacy</title>
    <link rel="stylesheet" href="./bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="./jquery-3.7.1.min.js"></script>
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
        .error-message {
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }
        .is-invalid {
            border-color: #dc3545 !important;
        }
        .is-valid {
            border-color: #28a745 !important;
        }
        .password-requirements {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }
        .requirement {
            display: flex;
            align-items: center;
            margin-bottom: 0.25rem;
        }
        .requirement i {
            margin-right: 0.5rem;
            font-size: 0.7rem;
        }
        .valid {
            color: #28a745;
        }
        .invalid {
            color: #dc3545;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4" style="width: 400px;">
        <!-- Bootstrap Alert -->
        <?php if (!empty($alert_message)): ?>
            <div class="alert alert-<?php echo $alert_type; ?> alert-dismissible fade show" role="alert">
                <?php echo $alert_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <h3 class="text-center text-success">Sign Up</h3>
        <p class="text-center text-muted">Create your account</p>

        <form method="POST" id="signupForm" novalidate>
            <div class="mb-3">
                <label class="form-label text-success">Username</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Enter username" required>
                <div class="error-message" id="usernameError"></div>
            </div>

            <div class="mb-3">
                <label class="form-label text-success">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter email" required>
                <div class="error-message" id="emailError"></div>
            </div>

            <div class="mb-3">
                <label class="form-label text-success">Password</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
                    <span class="input-group-text" onclick="togglePassword('password', 'eye-icon1')">
                        <i class="fas fa-eye" id="eye-icon1"></i>
                    </span>
                </div>
                
                <div class="error-message" id="passwordError"></div>
            </div>

            <div class="mb-3">
                <label class="form-label text-success">Confirm Password</label>
                <div class="input-group">
                    <input type="password" id="confirm-password" name="confirm-password" class="form-control" placeholder="Confirm password" required>
                    <span class="input-group-text" onclick="togglePassword('confirm-password', 'eye-icon2')">
                        <i class="fas fa-eye" id="eye-icon2"></i>
                    </span>
                </div>
                <div class="error-message" id="confirmPasswordError"></div>
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
        const passwordField = document.getElementById(fieldId);
        const eyeIcon = document.getElementById(iconId);
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

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('signupForm');
        const usernameInput = document.getElementById('username');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirm-password');
        
        // Password requirement icons
        const lengthIcon = document.getElementById('length-icon');
        const uppercaseIcon = document.getElementById('uppercase-icon');
        const lowercaseIcon = document.getElementById('lowercase-icon');
        const numberIcon = document.getElementById('number-icon');
        const specialIcon = document.getElementById('special-icon');

        // Validate username (3-20 chars, letters and numbers only)
        function validateUsername(username) {
            const re = /^[a-zA-Z0-9]{3,20}$/;
            return re.test(username);
        }

        // Validate email format
        function validateEmail(email) {
            const re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            return re.test(String(email).toLowerCase());
        }

        // Validate password strength
        function validatePassword(password) {
            const hasMinLength = password.length >= 8;
            const hasUpperCase = /[A-Z]/.test(password);
            const hasLowerCase = /[a-z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);
            
            // Update requirement icons
            lengthIcon.classList.toggle('valid', hasMinLength);
            lengthIcon.classList.toggle('invalid', !hasMinLength);
            uppercaseIcon.classList.toggle('valid', hasUpperCase);
            uppercaseIcon.classList.toggle('invalid', !hasUpperCase);
            lowercaseIcon.classList.toggle('valid', hasLowerCase);
            lowercaseIcon.classList.toggle('invalid', !hasLowerCase);
            numberIcon.classList.toggle('valid', hasNumber);
            numberIcon.classList.toggle('invalid', !hasNumber);
            specialIcon.classList.toggle('valid', hasSpecialChar);
            specialIcon.classList.toggle('invalid', !hasSpecialChar);
            
            return hasMinLength && hasUpperCase && hasLowerCase && hasNumber && hasSpecialChar;
        }

        // Validate form on submit
        form.addEventListener('submit', function(event) {
            let isValid = true;
            
            // Reset errors
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
            document.querySelectorAll('.form-control').forEach(el => {
                el.classList.remove('is-invalid', 'is-valid');
            });

            // Validate username
            if (!usernameInput.value.trim()) {
                document.getElementById('usernameError').textContent = 'Username is required';
                usernameInput.classList.add('is-invalid');
                isValid = false;
            } else if (!validateUsername(usernameInput.value.trim())) {
                document.getElementById('usernameError').textContent = 'Username must be 3-20 characters (letters and numbers only)';
                usernameInput.classList.add('is-invalid');
                isValid = false;
            } else {
                usernameInput.classList.add('is-valid');
            }

            // Validate email
            if (!emailInput.value.trim()) {
                document.getElementById('emailError').textContent = 'Email is required';
                emailInput.classList.add('is-invalid');
                isValid = false;
            } else if (!validateEmail(emailInput.value.trim())) {
                document.getElementById('emailError').textContent = 'Please enter a valid email address';
                emailInput.classList.add('is-invalid');
                isValid = false;
            } else {
                emailInput.classList.add('is-valid');
            }

            // Validate password
            if (!passwordInput.value) {
                document.getElementById('passwordError').textContent = 'Password is required';
                passwordInput.classList.add('is-invalid');
                isValid = false;
            } else if (!validatePassword(passwordInput.value)) {
                document.getElementById('passwordError').textContent = 'Password does not meet requirements';
                passwordInput.classList.add('is-invalid');
                isValid = false;
            } else {
                passwordInput.classList.add('is-valid');
            }

            // Validate confirm password
            if (!confirmPasswordInput.value) {
                document.getElementById('confirmPasswordError').textContent = 'Please confirm your password';
                confirmPasswordInput.classList.add('is-invalid');
                isValid = false;
            } else if (confirmPasswordInput.value !== passwordInput.value) {
                document.getElementById('confirmPasswordError').textContent = 'Passwords do not match';
                confirmPasswordInput.classList.add('is-invalid');
                isValid = false;
            } else {
                confirmPasswordInput.classList.add('is-valid');
            }

            if (!isValid) {
                event.preventDefault();
            }
        });

        // Real-time validation for username
        usernameInput.addEventListener('input', function() {
            if (!this.value.trim()) {
                document.getElementById('usernameError').textContent = 'Username is required';
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else if (!validateUsername(this.value.trim())) {
                document.getElementById('usernameError').textContent = 'Username must be 3-20 characters (letters and numbers only)';
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                document.getElementById('usernameError').textContent = '';
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });

        // Real-time validation for email
        emailInput.addEventListener('input', function() {
            if (!this.value.trim()) {
                document.getElementById('emailError').textContent = 'Email is required';
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else if (!validateEmail(this.value.trim())) {
                document.getElementById('emailError').textContent = 'Please enter a valid email address';
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                document.getElementById('emailError').textContent = '';
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });

        // Real-time validation for password
        passwordInput.addEventListener('input', function() {
            if (!this.value) {
                document.getElementById('passwordError').textContent = 'Password is required';
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                validatePassword(this.value);
                if (validatePassword(this.value)) {
                    document.getElementById('passwordError').textContent = '';
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    document.getElementById('passwordError').textContent = '';
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                }
            }
            
            // Also validate confirm password if it has value
            if (confirmPasswordInput.value) {
                if (confirmPasswordInput.value !== this.value) {
                    document.getElementById('confirmPasswordError').textContent = 'Passwords do not match';
                    confirmPasswordInput.classList.add('is-invalid');
                    confirmPasswordInput.classList.remove('is-valid');
                } else {
                    document.getElementById('confirmPasswordError').textContent = '';
                    confirmPasswordInput.classList.remove('is-invalid');
                    confirmPasswordInput.classList.add('is-valid');
                }
            }
        });

        // Real-time validation for confirm password
        confirmPasswordInput.addEventListener('input', function() {
            if (!this.value) {
                document.getElementById('confirmPasswordError').textContent = 'Please confirm your password';
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else if (this.value !== passwordInput.value) {
                document.getElementById('confirmPasswordError').textContent = 'Passwords do not match';
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                document.getElementById('confirmPasswordError').textContent = '';
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    });
</script>
<script src="./jquery/bootstrap.bundle.min.js"></script>

</body>
</html>