<?php

if (isset($paymentIntentID)) {
    require_once '../../vendor/autoload.php';
    require_once 'secrets.php';
    
    $stripe = new \Stripe\StripeClient($stripeSecretKey);
    
    try {
        $paymentIntent = $stripe->paymentIntents->retrieve($paymentIntentID);
        
        if ($paymentIntent->status === 'succeeded') {
            $paymentMethodID = $paymentIntent->payment_method;
            $totalAmount = number_format(($paymentIntent->amount) / 100.0, 2);
        
            $paymentMethod = $stripe->paymentMethods->retrieve($paymentMethodID);
            $paymentType = $paymentMethod->type;
        }
    } catch (Exception $ex) {
        "Error: " . $ex->getMessage();
    }
}

