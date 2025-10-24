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
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Verify Your Account - IT Sahayata</title>
            </head>
            <body style='margin: 0; padding: 0; font-family: Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);'>
                <div style='max-width: 600px; margin: 40px auto; background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1);'>
                    <!-- Header with Logo -->
                    <div style='background: linear-gradient(135deg, #007BFF 0%, #0056b3 100%); padding: 30px; text-align: center;'>
                        <img src='https://itsahayata.com/assets/logo.svg' alt='IT Sahayata Logo' style='height: 60px; margin-bottom: 15px;'>
                        <h1 style='color: white; margin: 0; font-size: 28px; font-weight: 600;'>IT Sahayata</h1>
                        <p style='color: rgba(255,255,255,0.9); margin: 10px 0 0 0; font-size: 16px;'>Your IT Support Partner</p>
                    </div>
                    
                    <!-- Content -->
                    <div style='padding: 40px;'>
                        <h2 style='color: #333; margin-bottom: 25px; font-size: 24px;'>Welcome to IT Sahayata!</h2>
                        
                        <p style='color: #666; line-height: 1.6; margin-bottom: 20px;'>Hi <strong style='color: #007BFF;'>$toName</strong>,</p>
                        
                        <p style='color: #666; line-height: 1.6; margin-bottom: 25px;'>Thank you for creating an account with us. Your One-Time Password (OTP) for verification is:</p>
                        
                        <!-- OTP Box -->
                        <div style='background: linear-gradient(135deg, #00C853 0%, #009624 100%); color: white; padding: 25px; border-radius: 12px; text-align: center; margin: 30px 0;'>
                            <h3 style='margin: 0 0 15px 0; font-size: 18px; font-weight: 500;'>Your Verification Code</h3>
                            <div style='font-size: 42px; font-weight: bold; letter-spacing: 8px; background: rgba(255,255,255,0.2); padding: 15px; border-radius: 8px; margin: 0 auto; max-width: 300px;'>$otp</div>
                        </div>
                        
                        <p style='color: #666; line-height: 1.6; margin-bottom: 25px;'><strong>⚠️ Important:</strong> This OTP is valid for <strong style='color: #ff6b6b;'>10 minutes</strong> only.</p>
                        
                        <div style='background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #007BFF; margin: 25px 0;'>
                            <p style='color: #666; margin: 0; font-size: 14px; line-height: 1.5;'>
                                <strong>Next Steps:</strong><br>
                                1. Enter this OTP in the verification page<br>
                                2. Complete your profile setup<br>
                                3. Start using our IT support services
                            </p>
                        </div>
                        
                        <p style='color: #999; font-size: 14px; line-height: 1.5; margin-bottom: 0;'>
                            If you didn't request this verification, please ignore this email or contact our support team immediately.
                        </p>
                    </div>
                    
                    <!-- Footer -->
                    <div style='background: #f8f9fa; padding: 25px; text-align: center; border-top: 1px solid #e9ecef;'>
                        <p style='color: #6c757d; margin: 0; font-size: 14px;'>
                            © 2024 IT Sahayata. All rights reserved.<br>
                            <span style='color: #007BFF;'>support@itsahayata.com</span> | +91-XXXXXXXXXX
                        </p>
                    </div>
                </div>
            </body>
            </html>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function sendWelcomeEmail($toEmail, $toName) {
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
        $mail->Subject = 'Welcome to IT Sahayata!';
        $mail->Body = "
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Welcome to IT Sahayata</title>
            </head>
            <body style='margin: 0; padding: 0; font-family: Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);'>
                <div style='max-width: 600px; margin: 40px auto; background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1);'>
                    <!-- Header with Logo -->
                    <div style='background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 40px; text-align: center;'>
                        <img src='https://itsahayata.com/assets/logo.svg' alt='IT Sahayata Logo' style='height: 70px; margin-bottom: 20px;'>
                        <h1 style='color: white; margin: 0; font-size: 32px; font-weight: 600;'>Welcome Aboard!</h1>
                        <p style='color: rgba(255,255,255,0.9); margin: 15px 0 0 0; font-size: 18px;'>Your IT Support Journey Begins Here</p>
                    </div>
                    
                    <!-- Content -->
                    <div style='padding: 40px;'>
                        <h2 style='color: #333; margin-bottom: 25px; font-size: 26px;'>Hello <strong style='color: #28a745;'>$toName</strong>! 👋</h2>
                        
                        <p style='color: #666; line-height: 1.6; margin-bottom: 20px;'>
                            Welcome to <strong style='color: #007BFF;'>IT Sahayata</strong> - your trusted partner for all IT support needs!
                        </p>
                        
                        <p style='color: #666; line-height: 1.6; margin-bottom: 25px;'>
                            We're thrilled to have you on board. Your account has been successfully verified and is now active.
                        </p>
                        
                        <!-- Features Grid -->
                        <div style='display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin: 30px 0;'>
                            <div style='background: #f8f9fa; padding: 20px; border-radius: 10px; text-align: center; border: 1px solid #e9ecef;'>
                                <div style='font-size: 32px; color: #007BFF; margin-bottom: 10px;'>🚀</div>
                                <h4 style='margin: 0 0 10px 0; color: #333;'>Quick Support</h4>
                                <p style='margin: 0; color: #666; font-size: 14px;'>24/7 technical assistance</p>
                            </div>
                            <div style='background: #f8f9fa; padding: 20px; border-radius: 10px; text-align: center; border: 1px solid #e9ecef;'>
                                <div style='font-size: 32px; color: #28a745; margin-bottom: 10px;'>🔧</div>
                                <h4 style='margin: 0 0 10px 0; color: #333;'>Expert Solutions</h4>
                                <p style='margin: 0; color: #666; font-size: 14px;'>Professional IT troubleshooting</p>
                            </div>
                            <div style='background: #f8f9fa; padding: 20px; border-radius: 10px; text-align: center; border: 1px solid #e9ecef;'>
                                <div style='font-size: 32px; color: #ff6b6b; margin-bottom: 10px;'>⏰</div>
                                <h4 style='margin: 0 0 10px 0; color: #333;'>Fast Response</h4>
                                <p style='margin: 0; color: #666; font-size: 14px;'>Quick ticket resolution</p>
                            </div>
                            <div style='background: #f8f9fa; padding: 20px; border-radius: 10px; text-align: center; border: 1px solid #e9ecef;'>
                                <div style='font-size: 32px; color: #6f42c1; margin-bottom: 10px;'>📊</div>
                                <h4 style='margin: 0 0 10px 0; color: #333;'>Live Tracking</h4>
                                <p style='margin: 0; color: #666; font-size: 14px;'>Real-time ticket updates</p>
                            </div>
                        </div>
                        
                        <!-- Call to Action -->
                        <div style='background: linear-gradient(135deg, #007BFF 0%, #0056b3 100%); padding: 25px; border-radius: 12px; text-align: center; margin: 30px 0;'>
                            <h3 style='color: white; margin: 0 0 15px 0; font-size: 20px;'>Ready to Get Started?</h3>
                            <p style='color: rgba(255,255,255,0.9); margin: 0 0 20px 0; line-height: 1.5;'>
                                Log in to your account and submit your first support ticket!
                            </p>
                            <a href='https://itsahayata.com/login' style='display: inline-block; background: white; color: #007BFF; padding: 12px 30px; text-decoration: none; border-radius: 25px; font-weight: bold; font-size: 16px; transition: all 0.3s ease;'>Go to Dashboard</a>
                        </div>
                        
                        <div style='background: #fff3cd; padding: 20px; border-radius: 8px; border-left: 4px solid #ffc107; margin: 25px 0;'>
                            <h4 style='color: #856404; margin: 0 0 10px 0; font-size: 16px;'>💡 Pro Tip:</h4>
                            <p style='color: #856404; margin: 0; font-size: 14px; line-height: 1.5;'>
                                For faster support, please provide detailed information about your issue when submitting a ticket.
                            </p>
                        </div>
                        
                        <p style='color: #666; line-height: 1.6; margin-bottom: 0;'>
                            If you have any questions or need assistance, our support team is always here to help!
                        </p>
                    </div>
                    
                    <!-- Footer -->
                    <div style='background: #343a40; padding: 30px; text-align: center;'>
                        <p style='color: #fff; margin: 0 0 15px 0; font-size: 16px; font-weight: 500;'>Happy Computing! 💻</p>
                        <p style='color: #adb5bd; margin: 0; font-size: 14px;'>
                            © 2024 IT Sahayata. All rights reserved.<br>
                            <span style='color: #20c997;'>support@itsahayata.com</span> | +91-XXXXXXXXXX
                        </p>
                        <div style='margin-top: 20px;'>
                            <a href='https://itsahayata.com' style='color: #20c997; text-decoration: none; margin: 0 15px; font-size: 14px;'>Website</a>
                            <a href='https://itsahayata.com/support' style='color: #20c997; text-decoration: none; margin: 0 15px; font-size: 14px;'>Support</a>
                            <a href='https://itsahayata.com/contact' style='color: #20c997; text-decoration: none; margin: 0 15px; font-size: 14px;'>Contact</a>
                        </div>
                    </div>
                </div>
            </body>
            </html>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
