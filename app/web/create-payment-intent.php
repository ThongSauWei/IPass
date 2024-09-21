<?php

require_once '../../vendor/autoload.php';
require_once 'secrets.php';

$stripe = new \Stripe\StripeClient($stripeSecretKey);

header('Content-Type: application/json');

try {
    $jsonStr = file_get_contents('php://input');
    $jsonObj = json_decode($jsonStr);

    $paymentIntent = $stripe->paymentIntents->create([
        'amount' => $jsonObj->totalAmount,
        'currency' => 'myr',
//        'automatic_payment_methods' => [
//            'enabled' => true,
//        ],
        'payment_method_types' => ['card', 'fpx'],
    ]);

    $output = [
        'clientSecret' => $paymentIntent->client_secret,
        'paymentIntentID' => $paymentIntent->id,
    ];

    echo json_encode($output);
} catch (Exception $ex) {
    http_response_code(500);
    echo $ex->getMessage();
    echo json_encode(["error" => $ex->getMessage()]);
}


