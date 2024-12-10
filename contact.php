<?php
header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Secure inclusion of the config file
define('SECURE_CONSTANT', true);
$config = require 'config.php';

$response = [
    'success' => false,
    'message' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and collect form data
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $subject = htmlspecialchars(trim($_POST['subject'] ?? ''));
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));

    // Validate required fields
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $response['message'] = 'All fields are required.';
        echo json_encode($response);
        exit;
    }

    // Validate email format
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
        $mail->Host = $config['smtp_host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['smtp_username'];
        $mail->Password = $config['smtp_password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $config['smtp_port'];
    
        // Sender and recipient settings
        $mail->setFrom($config['from_email'], $config['from_name']);
        $mail->addAddress($config['to_email'], "Admin");
        $mail->addReplyTo($email, $name); // Reply-to set to the form submitter's email

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "$subject (Message from $name)";
        $mail->Body = "
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Message:</strong></p>
            <p>" . nl2br($message) . "</p>
        ";
        $mail->AltBody = "Name: $name\nEmail: $email\n\nMessage:\n$message";

        // Send the email
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

// Return the response as JSON
echo json_encode($response);
