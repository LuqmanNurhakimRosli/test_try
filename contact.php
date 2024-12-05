<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Collect form data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$subject = $_POST['subject'] ?? '';
$message = $_POST['message'] ?? '';

// Validate input
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
    exit;
}

// Construct email message
$to = 'luqman@dagangnet.com';
$email_subject = "New contact form submission: $subject";
$email_body = "You have received a new message from your website contact form.\n\n" .
    "Here are the details:\n\nName: $name\n\nEmail: $email\n\nSubject: $subject\n\nMessage:\n$message";
$headers = "From: noreply@yourdomain.com\n";
$headers .= "Reply-To: $email";

// Attempt to send email and log the result
$log_message = date('Y-m-d H:i:s') . " - Attempting to send email:\n" .
    "To: $to\nSubject: $email_subject\nBody: $email_body\nHeaders: $headers\n";

if (mail($to, $email_subject, $email_body, $headers)) {
    $log_message .= "Email sent successfully.\n";
    echo json_encode(['success' => true, 'message' => 'Thank you for your message. We will get back to you soon.']);
} else {
    $log_message .= "Failed to send email.\n";
    echo json_encode(['success' => false, 'message' => 'Oops! Something went wrong and we couldn\'t send your message.']);
}

// Log the attempt
file_put_contents('email_log.txt', $log_message . "\n", FILE_APPEND);
?>