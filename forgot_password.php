<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHUSHRUTA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="./jquery-3.7.1.min.js"></script>
    <script src="./jquery.validate.min.js"></script>
    <link rel="shortcut icon" href="./assets/logo2.jpg" type="image/x-icon">

    <style>
        .error {
            color: red;
        }
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
        }
        .form-header {
            background-color: #2c6e49;
            color: white;
            text-align: center;
            padding: 20px;
        }
        .form-body {
            padding: 20px;
        }
        .form-body label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        .form-body input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        .form-body input[type="submit"] {
            background-color: #2c6e49;
            color: white;
            font-size: 1rem;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        .form-body input[type="submit"]:hover {
            background-color: #25543e;
        }
        .form-footer {
            text-align: center;
            padding: 15px;
            background: #f4f4f4;
        }
        .form-footer a {
            color: #2c6e49;
            text-decoration: none;
            font-weight: bold;
        }
        .form-footer a:hover {
            text-decoration: underline;
        }
        .error {
            color: red !important;
            font-style: italic;
        }

    </style>
</head>
<body>
<div class="container">
    <div class="form-header">
        <h2>Forgot Password</h2>
    </div>
    <div class="form-body">
        <form id="forgotPasswordForm" action="forgot-password-handler.php" method="POST">
            <label for="email">Enter your email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            <input type="submit" value="Reset Password">
        </form>
    </div>
    <div class="form-footer">
        <a href="login.php">Back to Login</a>
    </div>
</div>

<script>
    $(document).ready(function(){
        $("#forgotPasswordForm").validate({
            rules: {
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                email: {
                    required: "Please enter your email address.",
                    email: "Please enter a valid email address."
                }
            }
        });
    });
</script>
</body>
</html>
