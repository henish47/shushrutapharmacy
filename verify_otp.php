<?php
session_start();
include 'config.php';

if (!isset($_SESSION['email'])) {
    die("Session expired. Please request a new OTP.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_SESSION['email'];
    $entered_otp = implode("", $_POST['otp']); // Combine OTP digits into a full OTP string

    $query = "SELECT otp_code, otp_expiry FROM sign_up WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $otp_code = $row['otp_code'];
        $otp_expiry = $row['otp_expiry'];

        if ($entered_otp == $otp_code && strtotime($otp_expiry) > time()) {
            $_SESSION['message'] = "OTP Verified Successfully!";
            $conn->query("UPDATE sign_up SET otp_code=NULL, otp_expiry=NULL WHERE email='$email'");
            header("Location: reset_password.php");
            exit();
        } else {
            $_SESSION['message'] = "Invalid or expired OTP.";
        }
    } else {
        $_SESSION['message'] = "No OTP found. Please request again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - Sushruta Pharmacy</title>
   
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
        .otp-input {
            width: 50px;
            height: 50px;
            font-size: 24px;
            text-align: center;
            border: 2px solid #28a745;
            border-radius: 8px;
            margin: 5px;
            outline: none;
        }
        .otp-input:focus {
            border-color: #1e7e34;
            box-shadow: 0 0 5px rgba(40, 167, 69, 0.5);
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4" style="width: 400px;">
        <h3 class="text-center text-success">Verify OTP</h3>
        <p class="text-center text-muted">Enter the OTP sent to your email</p>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info text-center">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="d-flex justify-content-center">
                <input type="text" name="otp[]" class="otp-input" maxlength="1" required oninput="moveToNext(this, 0)">
                <input type="text" name="otp[]" class="otp-input" maxlength="1" required oninput="moveToNext(this, 1)">
                <input type="text" name="otp[]" class="otp-input" maxlength="1" required oninput="moveToNext(this, 2)">
                <input type="text" name="otp[]" class="otp-input" maxlength="1" required oninput="moveToNext(this, 3)">
                <input type="text" name="otp[]" class="otp-input" maxlength="1" required oninput="moveToNext(this, 4)">
                <input type="text" name="otp[]" class="otp-input" maxlength="1" required oninput="moveToNext(this, 5)">
            </div>
            <button type="submit" class="btn btn-custom w-100 mt-3">Verify OTP</button>
        </form>

        <div class="text-center mt-3">
            <a href="forgot_password.php" class="text-decoration-none text-danger">Resend OTP</a>
        </div>
    </div>
</div>

<script>
    function moveToNext(input, index) {
        let nextInput = document.getElementsByClassName("otp-input")[index + 1];
        let prevInput = document.getElementsByClassName("otp-input")[index - 1];

        if (input.value.length === 1 && nextInput) {
            nextInput.focus();
        }
        if (input.value.length === 0 && prevInput) {
            prevInput.focus();
        }
    }
</script>

</body>
</html>
