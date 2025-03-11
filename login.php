<?php
session_start();
require 'config.php'; // Database connection

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if user exists in the database
    $stmt = $conn->prepare("SELECT * FROM sign_up WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Check if password matches
        if (password_verify($password, $user['password'])) {
            if ($user['verified'] == 1) {
                // Store user data in session
                $_SESSION['logged_in'] = true;
                $_SESSION['user'] = [
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'profile_pic' => $user['profile_pic'] ?? 'assets/profile.jpg' // Default if empty
                ];

                // Redirect to refresh navbar with new session data
                header("Location: index.php");
                exit();
            } else {
                $error = "Your email is not verified. Please check your inbox.";
            }
        } else {
            $error = "Invalid email or password!";
        }
    } else {
        $error = "No user found with this email!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHUSHRUTA | Login</title>
    <link rel="stylesheet" href="styles.css">
    <!-- <script src="./jquery-3.7.1.min.js"></script> 
    <script src="./jquery.validate.min.js"></script>
    <script src="./additional-methods.js"></script> -->
    <script src="./jquery-3.7.1.min.js"></script>
    <script src="./jquery.validate.min.js"></script>
    <script src="./additional-methods.js"></script>
   
    <script src="./validtion.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 { color: #2c6e49; }
        label {
            display: block;
            text-align: left;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .btn {
            background-color: #2c6e49;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        .btn:hover {
            background-color: #25543e;
        }
        .error {
            color: red;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 15px;
        }
        .footer a {
            color: #2c6e49;
            font-weight: bold;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>

        <?php if (!empty($error)) { ?>
            <p class="error"><?= $error; ?></p>
        <?php } ?>

        <form action="login.php" method="POST" class="needs-validation" id="myForm" novalidate>
       
            <label for="email" >Email</label>
            <input type="email" name="email"  data-validation="required email" required>
            <span id="emailError" class="text-danger error"></span>

            <label for="password">Password</label>
            <input type="password" name="password" data-validation="required password" required>
            <span id="passwordError" class="text-danger error"></span>

            <button type="submit" class="btn">Login</button>
        </form>

        <div class="footer">
            <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
            <p><a href="forgot_password.php">Forgot Password?</a></p>
        </div>
    </div>
</body>
</html>
