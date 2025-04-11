<?php
session_start();
include 'config.php';

// Ensure session is set correctly
if (!isset($_SESSION['user']['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['user']['email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        echo "<div class='alert alert-warning text-center'>Passwords do not match.</div>";
    } else {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Update the password in the database
        $stmt = $conn->prepare("UPDATE sign_up SET password=? WHERE email=?");
        if (!$stmt) {
            die("Prepare statement failed: " . $conn->error);
        }

        $stmt->bind_param("ss", $hashed_password, $email);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success text-center'>Password changed successfully! Redirecting to login...</div>";
            
            // Destroy session & redirect
            session_destroy();
            echo "<script>setTimeout(() => { window.location.href = 'login.php'; }, 2000);</script>";
            exit();
        } else {
            echo "<div class='alert alert-danger text-center'>Error updating password.</div>";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Sushruta Pharmacy</title>
    <link rel="stylesheet" href="./bootstrap.min.css">
    <script src="./bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

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
<?php include './navbar.php'; ?>
<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4" style="width: 400px;">
        <h3 class="text-center text-success">Change Password</h3>
        <p class="text-center text-muted">Update your account password</p>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo isset($_SESSION['alert_type']) ? $_SESSION['alert_type'] : 'info'; ?> text-center">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <form method="post" id="changePasswordForm" novalidate>
            <div class="mb-3">
                <label class="form-label">Current Password</label>
                <div class="input-group">
                    <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Enter current password" required>
                    <span class="input-group-text" onclick="togglePassword('current_password', 'eye-icon0')">
                        <i class="fas fa-eye" id="eye-icon0"></i>
                    </span>
                </div>
                <div class="error-message" id="currentPasswordError"></div>
            </div>

            <div class="mb-3">
                <label class="form-label">New Password</label>
                <div class="input-group">
                    <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Enter new password" required>
                    <span class="input-group-text" onclick="togglePassword('new_password', 'eye-icon1')">
                        <i class="fas fa-eye" id="eye-icon1"></i>
                    </span>
                </div>
                <div class="error-message" id="newPasswordError"></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm New Password</label>
                <div class="input-group">
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm new password" required>
                    <span class="input-group-text" onclick="togglePassword('confirm_password', 'eye-icon2')">
                        <i class="fas fa-eye" id="eye-icon2"></i>
                    </span>
                </div>
                <div class="error-message" id="confirmPasswordError"></div>
            </div>

            <button type="submit" class="btn btn-success w-100">Change Password</button>
        </form>

        <div class="text-center mt-3">
            <a href="./index.php" class="text-decoration-none text-danger">Back to Home</a>
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
        const form = document.getElementById('changePasswordForm');
        const currentPasswordInput = document.getElementById('current_password');
        const newPasswordInput = document.getElementById('new_password');
        const confirmPasswordInput = document.getElementById('confirm_password');
        
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

            // Validate current password
            if (!currentPasswordInput.value) {
                document.getElementById('currentPasswordError').textContent = 'Current password is required';
                currentPasswordInput.classList.add('is-invalid');
                isValid = false;
            } else {
                currentPasswordInput.classList.add('is-valid');
            }

            // Validate new password
            if (!newPasswordInput.value) {
                document.getElementById('newPasswordError').textContent = 'New password is required';
                newPasswordInput.classList.add('is-invalid');
                isValid = false;
            } else if (!validatePassword(newPasswordInput.value)) {
                document.getElementById('newPasswordError').textContent = 'Password does not meet requirements';
                newPasswordInput.classList.add('is-invalid');
                isValid = false;
            } else if (newPasswordInput.value === currentPasswordInput.value) {
                document.getElementById('newPasswordError').textContent = 'New password must be different from current password';
                newPasswordInput.classList.add('is-invalid');
                isValid = false;
            } else {
                newPasswordInput.classList.add('is-valid');
            }

            // Validate confirm password
            if (!confirmPasswordInput.value) {
                document.getElementById('confirmPasswordError').textContent = 'Please confirm your new password';
                confirmPasswordInput.classList.add('is-invalid');
                isValid = false;
            } else if (confirmPasswordInput.value !== newPasswordInput.value) {
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

        // Real-time validation for new password
        newPasswordInput.addEventListener('input', function() {
            if (!this.value) {
                document.getElementById('newPasswordError').textContent = 'New password is required';
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                validatePassword(this.value);
                if (validatePassword(this.value)) {
                    document.getElementById('newPasswordError').textContent = '';
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    document.getElementById('newPasswordError').textContent = '';
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
                document.getElementById('confirmPasswordError').textContent = 'Please confirm your new password';
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else if (this.value !== newPasswordInput.value) {
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

<?php include './footer.php'; ?>
</body>
</html>