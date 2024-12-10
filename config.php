<?php
// Prevent direct access to the configuration file
if (!defined('SECURE_CONSTANT')) {
    die('Direct access to this file is not allowed.');
}

// Return the configuration array
return [
    'smtp_host' => 'smtp.office365.com',
    'smtp_username' => 'luqman@dagangnet.com',
    'smtp_password' => 'Password@2',
    'smtp_port' => 587,
    'from_email' => 'luqman@dagangnet.com', // Static sender email (authenticated account)
    'from_name' => 'Ping Energy Marketing', // Sender name
    'to_email' => 'sarah.anisah@dnex.com.my' // Recipient email address
];
