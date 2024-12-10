<?php
header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Secure inclusion of config file
define('SECURE_CONSTANT', true);
$config = require 'config.php';

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
        $mail->Host = "smtp.office365.com";
        $mail->SMTPAuth = true;
        $mail->Username = "luqman@dagangnet.com"; // Authenticated account
        $mail->Password = "Password@2"; // Account password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
    
        // Sender and recipient settings
        $mail->setFrom("luqman@dagangnet.com", "Contact Form"); // Use authenticated account as sender
        $mail->addAddress("luqman@dagangnet.com", "Luqman");
        $mail->addReplyTo($email, $name); // Set reply-to as the form submitter's email

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "PEML Message from $name";
        $mail->Body = "
        <p><strong>Name:</strong> $name</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Message:</strong></p>
        <p>" . nl2br(htmlspecialchars($message)) . "</p>
        ";

        $mail->AltBody = "From: $name\nEmail: $email\n\nMessage:\n$message";

        $mail->send();
        $response['success'] = true;
        $response['message'] = 'Your message has been sent successfully.';
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        $response['message'] = "Message could not be sent. Please try again later.";
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);