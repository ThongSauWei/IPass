<?php

if ($paymentIntentID) {
    require_once '../../vendor/autoload.php';
    require_once 'secrets.php';
    
    $stripe = new \Stripe\StripeClient($stripeSecretKey);
    
    try {
        $paymentIntent = $stripe->paymentIntents->retrieve($paymentIntentID);
        
        $paymentMethodID = $paymentIntent->payment_method;
        $totalAmount = number_format(($paymentIntent->amount) / 100.0, 2);
        
        $paymentMethod = $stripe->paymentMethods->retrieve($paymentMethodID);
        
        var_dump($paymentMethod);
        
    } catch (Exception $ex) {
        "Error: " . $ex->getMessage();
    }
}

