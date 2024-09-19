<?php

require '../../vendor/autoload.php';

use SendGrid\Mail\Mail;

function sendPasswordRecoveryEmail($email, $token) {
    $emailMessage = new Mail();
    $emailMessage->setFrom("no-reply@nskgocery.com", "NSK Grogery");
    $emailMessage->setSubject("Password Recovery");
    
    //add recipient email
    $emailMessage->addTo($email, "User");
    
    $resetLink = "http://localhost/reset-password?token=" . $token;
    
    $emailMessage->addContent(
            "text/html",
            "<strong>Click the link below to reset your password:</strong><br><a href='$resetLink'>Reset Password</a>"
    );
    
    $sendgrid = new \SendGrid('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');
    
    try{
        //send email to user and return the status
        $response = $sendgrid->send($emailMessage);
        return $response->statusCode();
    }catch (Exception $e) {
        return 'Caught exception: ' . $e->getMessage();
    }
}
