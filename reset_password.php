<?php
session_start();
include 'config.php';

if (!isset($_SESSION['email'])) {
    die("Session expired. Please request a new OTP.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_SESSION['email'];
    $new_password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $conn->query("UPDATE sign_up SET password='$new_password' WHERE email='$email'");
    $_SESSION['message'] = "Password reset successfully!";
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Sushruta Pharmacy</title>
    <link rel="stylesheet" href="./bootstrap.min.css">
    <script src="./bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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
        <h3 class="text-center text-success">Reset Password</h3>
        <p class="text-center text-muted">Enter your new password below</p>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo isset($_SESSION['alert_type']) ? $_SESSION['alert_type'] : 'info'; ?> text-center">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <form method="post" id="resetPasswordForm" novalidate>
            <div class="mb-3">
                <label class="form-label text-success">New Password</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter new password" required>
                    <span class="input-group-text" onclick="togglePassword('password', 'eye-icon1')">
                        <i class="fas fa-eye" id="eye-icon1"></i>
                    </span>
                </div>
           
                <div class="error-message" id="passwordError"></div>
            </div>

            <div class="mb-3">
                <label class="form-label text-success">Confirm New Password</label>
                <div class="input-group">
                    <input type="password" id="confirm-password" name="confirm-password" class="form-control" placeholder="Confirm new password" required>
                    <span class="input-group-text" onclick="togglePassword('confirm-password', 'eye-icon2')">
                        <i class="fas fa-eye" id="eye-icon2"></i>
                    </span>
                </div>
                <div class="error-message" id="confirmPasswordError"></div>
            </div>

            <button type="submit" class="btn btn-custom w-100">Reset Password</button>
        </form>
        
        <div class="text-center mt-3">
            <a href="login.php" class="text-decoration-none text-danger">Back to Login</a>
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
        const form = document.getElementById('resetPasswordForm');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirm-password');
        
        // Password requirement icons
        const lengthIcon = document.getElementById('length-icon');
        const uppercaseIcon = document.getElementById('uppercase-icon');
        const lowercaseIcon = document.getElementById('lowercase-icon');
        const numberIcon = document.getElementById('number-icon');
        const specialIcon = document.getElementById('special-icon');

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

</body>
</html>