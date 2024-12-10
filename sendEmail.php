<?php
header('Content-type: application/json');

// Default status message
$status = array(
    'type' => 'success',
    'message' => 'Thank you for contacting us. We will get back to you as soon as possible.'
);

// Sanitize form inputs
$name = @trim(stripslashes($_POST['name'])); 
$email = @trim(stripslashes($_POST['email'])); 
$subject = @trim(stripslashes($_POST['subject'])); 
$message = @trim(stripslashes($_POST['message'])); 

// Email settings
$email_from = $email;
$email_to = 'luqman@dagangnet.com'; // Replace with the recipient's email

$body = 'Name: ' . $name . "\n\n" . 'Email: ' . $email . "\n\n" . 'Subject: ' . $subject . "\n\n" . 'Message: ' . $message;

// Send the email using PHP's mail() function
$success = @mail($email_to, $subject, $body, 'From: <'.$email_from.'>');

// If mail sending failed, change status message
if (!$success) {
    $status['type'] = 'error';
    $status['message'] = 'There was an error sending your message. Please try again later.';
}

// Return the JSON response
echo json_encode($status);
die;
