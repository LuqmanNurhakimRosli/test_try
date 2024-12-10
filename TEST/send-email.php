<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load Composer's autoloader
require "vendor/autoload.php";

// Collect form data
$name = $_POST["name"] ?? '';
$email = $_POST["email"] ?? '';
$message = $_POST["message"] ?? '';

// Initialize PHPMailer
$mail = new PHPMailer(true);

try {
    // Server settings
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
    $mail->Subject = "New Contact Form Message from $name";
    $mail->Body = "
    <p><strong>Name:</strong> $name</p>
    <p><strong>Email:</strong> $email</p>
    <p><strong>Message:</strong></p>
    <p>" . nl2br(htmlspecialchars($message)) . "</p>
    ";
    $mail->AltBody = "From: $name\nEmail: $email\n\nMessage:\n$message";

    // Send the email
    $mail->send();
    echo "Message sent successfully";
} catch (Exception $e) {
    $errorMessage = "Message could not be sent. Mailer Error: " . $mail->ErrorInfo;
    error_log($errorMessage); // Log the error
    echo $errorMessage; // Send error message back to the client
}
?>