<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/PHPMailer/Exception.php';
require './PHPMailer/PHPMailer/PHPMailer.php';
require './PHPMailer/PHPMailer/SMTP.php';

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['subject']) && !empty($_POST['message'])) {
        $name = htmlspecialchars($_POST['name']);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $subject = htmlspecialchars($_POST['subject']);
        $message = nl2br(htmlspecialchars($_POST['message']));

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'hsavaliya989@rku.ac.in';
            $mail->Password = 'ipsj iovb mkzj bydk'; // App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('hsavaliya989@rku.ac.in', 'Shushruta Pharmacy');
            $mail->addAddress('hsavaliya989@rku.ac.in');
            $mail->Subject = '📩 New Contact Form Submission - Shushruta Pharmacy';
            $mail->isHTML(true);
            $mail->Body = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
                        .container { width: 100%; padding: 20px; }
                        .email-content { background: #ffffff; padding: 25px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); max-width: 600px; margin: auto; }
                        .header { background: #007bff; color: #ffffff; padding: 15px; text-align: center; border-radius: 8px 8px 0 0; }
                        .header h2 { margin: 0; }
                        .details { padding: 20px; }
                        .details p { font-size: 16px; color: #333; margin-bottom: 10px; }
                        .footer { text-align: center; margin-top: 20px; font-size: 14px; color: #777; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='email-content'>
                            <div class='header'>
                                <h2>📩 New Contact Form Submission</h2>
                            </div>
                            <div class='details'>
                                <p><strong>👤 Name:</strong> {$name}</p>
                                <p><strong>📧 Email:</strong> {$email}</p>
                                <p><strong>📝 Subject:</strong> {$subject}</p>
                                <p><strong>💬 Message:</strong><br> {$message}</p>
                            </div>
                            <div class='footer'>
                                <p>🚀 This email was sent from the <strong>Shushruta Pharmacy</strong> contact form.</p>
                            </div>
                        </div>
                    </div>
                </body>
                </html>
            ";
            $mail->AltBody = "Name: {$name}\nEmail: {$email}\nSubject: {$subject}\nMessage: {$message}";

            $mail->send();
            $success = "Email sent successfully!";
        } catch (Exception $e) {
            $error = "Email could not be sent. Error: " . $mail->ErrorInfo;
        }
    } else {
        $error = "All fields are required!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Shusruta Pharmacy</title>
    <link rel="stylesheet" href="./bootstrap.min.css">
    <script src="./jquery-3.7.1.min.js"></script>
</head>
<body>
<?php include "navbar.php"; ?>
<div class="container mt-5">
    <h2 class="text-center mb-4">Contact Us</h2>
    <?php if ($success) { echo "<div class='alert alert-success'>$success</div>"; } ?>
    <?php if ($error) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
    <form method="POST" action="" id="contactForm" novalidate>
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Subject</label>
            <input type="text" name="subject" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Message</label>
            <textarea name="message" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send Message</button>
    </form>
</div>
<br>
<?php include "footer.php"; ?>
<script src="./bootstrap.bundle.min.js"></script>
</body>
</html>
