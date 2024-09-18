<?php

require_once '../../vendor/autoload.php';
require_once 'secrets.php';

$stripe = new \Stripe\StripeClient($stripeSecretKey);

header('Content-Type: application/json');

try {
    $jsonStr = file_get_contents('php://input');
    $jsonObj = json_decode($jsonStr, true);
    $paymentIntentID = $jsonObj->payment_intent_id;

    $paymentIntent = $stripe->paymentIntents->retrieve($paymentIntentID);
    $paymentIntent->cancel();

    echo json_encode(['status' => 'success']);
} catch (Exception $ex) {
    http_response_code(500);
    echo $ex->getMessage();
    echo json_encode(["error" => $ex->getMessage()]);
}

