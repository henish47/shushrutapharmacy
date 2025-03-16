<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Sushruta Pharmacy</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

        <form method="post" action="send_otp.php">
            <div class="mb-3">
                <label class="form-label text-success">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>
            <button type="submit" class="btn btn-custom w-100">Send OTP</button>
        </form>

        <div class="text-center mt-3">
            <a href="login.php" class="text-decoration-none text-success">Back to Login</a>
        </div>
    </div>
</div>

</body>
</html>
