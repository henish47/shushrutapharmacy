<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/PHPMailer/Exception.php';
require './PHPMailer/PHPMailer/PHPMailer.php';
require './PHPMailer/PHPMailer/SMTP.php';

function sendOTP($to, $otp) {
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'hsavaliya989@rku.ac.in'; // Change to your email
        $mail->Password = 'ipsj iovb mkzj bydk'; // Use an App Password (not your normal password)
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Email Details
        $mail->setFrom('hsavaliya989@rku.ac.in', 'Sushruta Pharmacy');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = "Your OTP Code";
        $mail->Body = "Your OTP for password reset is: <b>$otp</b>. It will expire in 10 minutes.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
