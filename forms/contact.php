<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../assets/vendor/PHPMailer/src/Exception.php';
require '../assets/vendor/PHPMailer/src/PHPMailer.php';
require '../assets/vendor/PHPMailer/src/SMTP.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $namer = trim(htmlspecialchars($_POST['fullnamer']));
    $emailr = trim(htmlspecialchars($_POST['emailr']));
    $phoner = trim(htmlspecialchars($_POST['contactr']));
    $messager = trim(htmlspecialchars($_POST['messager']));

    // Basic Validation
    if (strlen($namer) < 3) {
        $response['message'] = "Name must be at least 3 characters.";
        echo json_encode($response);
        exit;
    }

    if (!filter_var($emailr, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = "Invalid email format.";
        echo json_encode($response);
        exit;
    }

    if (!preg_match("/^\d{8}$/", $phoner)) {
        $response['message'] = "Enter a valid 10-digit phone number.";
        echo json_encode($response);
        exit;
    }

    if (strlen($messager) < 10) {
        $response['message'] = "Message must be at least 10 characters.";
        echo json_encode($response);
        exit;
    }

    // Send Email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'muhsintp.develop@gmail.com';
        $mail->Password = 'puqn mdnh etmx ckwp';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom($emailr, $namer);
        $mail->addAddress('muhsintp925@gmail.com');

        $mail->isHTML(true);
        $mail->Subject = "New Request Consultation Submission $namer";
        $mail->Body = "
        <html>
        <head>
            <style>
                body {
                    font-family: 'Arial', sans-serif;
                    line-height: 1.6;
                    margin: 0;
                    padding: 0;
                    background-color: #f4f4f4;
                }
                .container {
                    max-width: 600px;
                    margin: 20px auto;
                    background: #ffffff;
                    border-radius: 10px;
                    overflow: hidden;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                }
                .header {
                    background: #2196F3;
                    color: #ffffff;
                    padding: 20px;
                    text-align: center;
                }
                .header h1 {
                    margin: 0;
                    font-size: 24px;
                    font-weight: 500;
                }
                .content {
                    padding: 30px;
                    background: #ffffff;
                }
                .info-block {
                    margin-bottom: 20px;
                    padding: 15px;
                    background: #f8f9fa;
                    border-radius: 5px;
                }
                .info-label {
                    color: #666;
                    font-size: 14px;
                    font-weight: bold;
                    margin-bottom: 5px;
                }
                .info-value {
                    color: #333;
                    font-size: 16px;
                    margin: 0;
                }
                .message-box {
                    background: #e3f2fd;
                    padding: 20px;
                    border-radius: 5px;
                    margin-top: 20px;
                }
                .footer {
                    background: #f8f9fa;
                    padding: 15px;
                    text-align: center;
                    font-size: 12px;
                    color: #666;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>New Request Consultation Submission</h1>
                </div>
                
                <div class='content'>
                    <div class='info-block'>
                        <div class='info-label'>Name</div>
                        <div class='info-value'>$namer</div>
                    </div>
                    
                    <div class='info-block'>
                        <div class='info-label'>Email</div>
                        <div class='info-value'>$emailr</div>
                    </div>
                    
                    <div class='info-block'>
                <div class='info-label'>Contact Number</div>
                <div class='info-value'><a href='tel:$phoner'>$phoner</a></div>
            </div>
                    
                    <div class='message-box'>
                        <div class='info-label'>Message</div>
                        <div class='info-value'>$messager</div>
                    </div>
                </div>
                
                <div class='footer'>
                    This is an automated message from your contact form.
                </div>
            </div>
        </body>
        </html>";

        $mail->send();
        $response['success'] = true;
        $response['message'] = 'Message sent successfully!';
    } catch (Exception $e) {
        $response['message'] = 'Error sending message. Please try again.';
    }
} else {
    $response['message'] = 'Invalid request!';
}

echo json_encode($response);
?>
