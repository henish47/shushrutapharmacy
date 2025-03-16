<?php
require 'config.php';

$message = ""; 

if (isset($_GET['token'])){
    $token = $_GET['token'];

    $checkQuery = "SELECT * FROM sign_up WHERE token = ? LIMIT 1";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Update user as verified
        $updateQuery = "UPDATE sign_up SET verified = 1 WHERE token = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("s", $token);

        if ($stmt->execute()) {
            $message = "<div style='padding: 15px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px;'>
                            ✅ Your email has been successfully verified! You can now <a href='login.php' style='color: #155724; font-weight: bold;'>log in</a>.
                        </div>";
        } else {
            $message = "<div style='padding: 15px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;'>
                            ❌ Verification failed. Please try again later.
                        </div>";
        }
    } else {
        $message = "<div style='padding: 15px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;'>
                        ❌ Invalid or expired verification link.
                    </div>";
    }
} else {
    $message = "<div style='padding: 15px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;'>
                    ❌ No token provided.
                </div>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>

        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            margin: 0;
            padding: 50px;
        }
        .container {
            max-width: 500px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: auto;
        }
        h2 {
            color: #333;
        }
        .message {
            margin-top: 20px;
        }

    </style>
</head>
<body>

<div class="container">

    <h2>Email Verification</h2>

    <div class="message">
        <?php echo $message; ?>
    </div>

</div>

</body>
</html>