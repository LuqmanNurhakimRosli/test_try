<?php
header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$response = [
    'success' => false,
    'message' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Validate required fields
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $response['message'] = 'All fields are required.';
        echo json_encode($response);
        exit;
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Invalid email address.';
        echo json_encode($response);
        exit;
    }

    // Sending email with PHPMailer
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com'; // Adjust based on your provider
        $mail->SMTPAuth = true;
        $mail->Username = 'luqman@dagangnet.com'; // Replace with your email
        $mail->Password = 'Password@2'; // Replace with your password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email Details
        $mail->setFrom('luqman@dagangnet.com', 'Ping Energy Marketing'); // Fixed sender email
        $mail->addAddress('luqman@dagangnet.com'); // Fixed recipient email (your inbox)
        $mail->addReplyTo($email, $name); // Reply-To set to user's email and name

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = '
            <p><strong>Name:</strong> ' . htmlspecialchars($name) . '</p>
            <p><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>
            <p><strong>Message:</strong><br>' . nl2br(htmlspecialchars($message)) . '</p>
        ';

        $mail->send();
        $response['success'] = true;
        $response['message'] = 'Your message has been sent successfully.';
    } catch (Exception $e) {
        $response['message'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
