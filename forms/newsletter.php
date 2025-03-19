
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../assets/vendor/PHPMailer/src/Exception.php';
require '../assets/vendor/PHPMailer/src/PHPMailer.php';
require '../assets/vendor/PHPMailer/src/SMTP.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim(htmlspecialchars($_POST['fullname']));
    $email = trim(htmlspecialchars($_POST['email']));
    $phone = trim(htmlspecialchars($_POST['contact']));
    $subject = trim(htmlspecialchars($_POST['subject']));
    $message = trim(htmlspecialchars($_POST['message']));

    // Basic Validation
    if (strlen($name) < 3) {
        $response['message'] = "Name must be at least 3 characters.";
        echo json_encode($response);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = "Invalid email format.";
        echo json_encode($response);
        exit;
    }

    if (!preg_match("/^\d{10}$/", $phone)) { // Corrected to 10 digits
        $response['message'] = "Enter a valid 10-digit phone number.";
        echo json_encode($response);
        exit;
    }

    if (strlen($subject) < 1) {
        $response['message'] = "Invalid Subject.";
        echo json_encode($response);
        exit;
    }

    if (strlen($message) < 10) {
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

        $mail->setFrom($email, $name);
        $mail->addAddress('muhsintp925@gmail.com');

        $mail->isHTML(true);
        $mail->Subject = "New Contact Form Submission from $name";
        $mail->Body = "
        <html>
        <head>
            <style>
                body {
                    font-family: 'Arial', sans-serif;
                    line-height: 1.6;
                    background-color: #f4f4f4;
                }
                .container {
                    max-width: 600px;
                    margin: 20px auto;
                    background: #ffffff;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                    padding: 20px;
                }
                .header {
                    background: #2196F3;
                    color: #ffffff;
                    padding: 15px;
                    text-align: center;
                    font-size: 20px;
                }
                .content {
                    padding: 20px;
                }
                .info-block {
                    margin-bottom: 15px;
                    padding: 10px;
                    background: #f8f9fa;
                    border-radius: 5px;
                }
                .info-label {
                    font-weight: bold;
                    color: #555;
                }
                .message-box {
                    background: #e3f2fd;
                    padding: 15px;
                    border-radius: 5px;
                    margin-top: 15px;
                }
                .footer {
                    background: #f8f9fa;
                    padding: 10px;
                    text-align: center;
                    font-size: 12px;
                    color: #666;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>New Contact Form Submission</div>
                
                <div class='content'>
                    <div class='info-block'>
                        <div class='info-label'>Name:</div>
                        <div>$name</div>
                    </div>

                    <div class='info-block'>
                        <div class='info-label'>Email:</div>
                        <div>$email</div>
                    </div>

                    <div class='info-block'>
                        <div class='info-label'>Subject:</div>
                        <div>$subject</div>
                    </div>

                    <div class='info-block'>
                        <div class='info-label'>Contact Number:</div>
                        <div><a href='tel:$phone'>$phone</a></div>
                    </div>
                    
                    <div class='message-box'>
                        <div class='info-label'>Message:</div>
                        <div>$message</div>
                    </div>
                </div>
                
                <div class='footer'>This is an automated message from your contact form.</div>
            </div>
        </body>
        </html>";

        $mail->send();
        $response['success'] = true;
        $response['message'] = 'Message sent successfully!';
    } catch (Exception $e) {
        $response['message'] = 'Error sending message: ' . $mail->ErrorInfo;
    }
} else {
    $response['message'] = 'Invalid request!';
}

echo json_encode($response);
?>
