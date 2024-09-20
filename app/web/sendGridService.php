<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use SendGrid\Mail\Mail;

function sendPasswordRecoveryEmail($email, $token) {
    $emailMessage = new \SendGrid\Mail\Mail();
    $emailMessage->setFrom("erika_fung26@outlook.com", "NSK Grocery");
    $emailMessage->setSubject("Password Recovery");

    // Add recipient email
    $emailMessage->addTo($email, "User");

    // Create the reset link with the token
    $resetLink = "http://localhost/IPass/app/views/Customer/PassRecoveryNewPass.php?token=" . $token;

    $emailMessage->addContent(
        "text/html",
            "<strong>Click the link below to reset your password:</strong><br><a href='$resetLink'>Reset Password</a>"
    );

    $sendgrid = new \SendGrid('');

    
    try {
        // Send the email and get the response
        $response = $sendgrid->send($emailMessage);
        
        // Log the entire response for debugging
        echo 'Status Code: ' . $response->statusCode() . "\n";
        echo 'Response Body: ' . $response->body() . "\n";
        echo 'Response Headers: ' . print_r($response->headers(), true) . "\n";
        
        // Check if the email was successfully sent
        if ($response->statusCode() == 202) {
            return 202; // Email sent successfully
        } else {
            // Log detailed information if there was an error
            return json_encode([
                'status' => 'error',
                'message' => 'Failed to send recovery email. Status: ' . $response->statusCode(),
                'response_body' => $response->body(),
                'response_headers' => $response->headers()
            ]);
        }

    } catch (Exception $e) {
        // Catch and return the exception message
        return json_encode([
            'status' => 'error',
            'message' => 'Exception caught: ' . $e->getMessage()
        ]);
    }
}
