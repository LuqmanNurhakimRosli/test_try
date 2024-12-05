<?php
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

// Send email
if (mail($to, $email_subject, $email_body, $headers)) {
    echo json_encode(['success' => true, 'message' => 'Thank you for your message. We will get back to you soon.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Oops! Something went wrong and we couldn\'t send your message.']);
}
?>