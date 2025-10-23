<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// require 'vendor/autoload.php';
require __DIR__ . '/../vendor/autoload.php';


function sendOTPEmail($toEmail, $toName, $otp) {
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'no-reply@itsahayata.com';
        $mail->Password = 'Support@1925';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('no-reply@itsahayata.com', 'IT Sahayata');
        $mail->addAddress($toEmail, $toName);

        $mail->isHTML(true);
        $mail->Subject = 'Verify Your IT Sahayata Account';
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; padding: 20px; background: #f4f4f4;'>
                <div style='max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 10px;'>
                    <h2 style='color: #007BFF;'>Welcome to IT Sahayata!</h2>
                    <p>Hi <strong>$toName</strong>,</p>
                    <p>Your OTP for account verification is:</p>
                    <h1 style='color: #00C853; letter-spacing: 5px;'>$otp</h1>
                    <p>This OTP is valid for 10 minutes.</p>
                    <p style='color: #999; font-size: 12px;'>If you didn't request this, please ignore this email.</p>
                </div>
            </div>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
