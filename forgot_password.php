<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Sushruta Pharmacy</title>
    <link rel="stylesheet" href="./bootstrap.min.css">
    <script src="./bootstrap.bundle.min.js"></script>
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
        .success-message {
            color: #28a745;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4" style="width: 400px;">
        <h3 class="text-center text-success">Forgot Password</h3>
        <p class="text-center text-muted">Enter your registered email to receive OTP</p>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info text-center">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="send_otp.php" id="forgotPasswordForm" novalidate>
            <div class="mb-3">
                <label class="form-label text-success">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your registered email" required>
                <div class="error-message" id="emailError"></div>
                <div class="success-message" id="emailSuccess"></div>
            </div>
            <button type="submit" class="btn btn-custom w-100">Send OTP</button>
        </form>

        <div class="text-center mt-3">
            <a href="login.php" class="text-decoration-none text-success">Back to Login</a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('forgotPasswordForm');
        const emailInput = document.getElementById('email');
        const emailError = document.getElementById('emailError');
        const emailSuccess = document.getElementById('emailSuccess');

        // Validate email format
        function validateEmail(email) {
            const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            return re.test(String(email).toLowerCase());
        }

        // Validate form on submit
        form.addEventListener('submit', function(event) {
            let isValid = true;
            
            // Reset messages
            emailError.textContent = '';
            emailSuccess.textContent = '';
            emailInput.classList.remove('is-invalid', 'is-valid');

            // Validate email
            if (!emailInput.value.trim()) {
                emailError.textContent = 'Email is required';
                emailInput.classList.add('is-invalid');
                isValid = false;
            } else if (!validateEmail(emailInput.value.trim())) {
                emailError.textContent = 'Please enter a valid email address';
                emailInput.classList.add('is-invalid');
                isValid = false;
            } else {
                emailInput.classList.add('is-valid');
                emailSuccess.textContent = 'Valid email format';
            }

            if (!isValid) {
                event.preventDefault();
            } else {
                // Here you could add AJAX to check if email exists in your system
                // before actually submitting the form
                // checkEmailExists(emailInput.value.trim());
            }
        });

        // Real-time validation for email
        emailInput.addEventListener('input', function() {
            if (!this.value.trim()) {
                emailError.textContent = '';
                emailSuccess.textContent = '';
                this.classList.remove('is-invalid', 'is-valid');
            } else if (!validateEmail(this.value.trim())) {
                emailError.textContent = 'Please enter a valid email address';
                emailSuccess.textContent = '';
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                emailError.textContent = '';
                emailSuccess.textContent = 'Valid email format';
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });

        // Optional: Check if email exists in database (would require AJAX)
        /*
        function checkEmailExists(email) {
            fetch('check_email.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'email=' + encodeURIComponent(email)
            })
            .then(response => response.json())
            .then(data => {
                if (!data.exists) {
                    emailError.textContent = 'This email is not registered';
                    emailInput.classList.add('is-invalid');
                    emailInput.classList.remove('is-valid');
                    emailSuccess.textContent = '';
                }
            })
            .catch(error => console.error('Error:', error));
        }
        */
    });
</script>

</body>
</html>