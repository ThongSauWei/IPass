<?php
require_once __DIR__ . '/../../../../facades/UserFacade.php';
require_once __DIR__ . '/XSLTransformation.php'; // Ensure this path is correct

// Fetch current date and time in Kuala Lumpur
$currentDateTime = (new DateTime('now', new DateTimeZone('Asia/Kuala_Lumpur')))->format('Y-m-d H:i:s');

// Generate XML dynamically
$userFacade = new UserFacade();
$customers = $userFacade->getAllCustomers(); // Fetch customers from the database

// Create XML content dynamically
$xml = new DOMDocument('1.0', 'UTF-8');
$root = $xml->createElement('customers');

foreach ($customers as $customer) {
    $customerNode = $xml->createElement('customer');

    $userID = $xml->createElement('UserID', $customer['UserID']);
    $customerNode->appendChild($userID);

    $customerID = $xml->createElement('CustomerID', $customer['CustomerID']);
    $customerNode->appendChild($customerID);

    $username = $xml->createElement('Username', $customer['Username']);
    $customerNode->appendChild($username);

    $email = $xml->createElement('Email', $customer['Email']);
    $customerNode->appendChild($email);

    $gender = $xml->createElement('Gender', $customer['Gender'] == 'm' ? 'Male' : 'Female');
    $customerNode->appendChild($gender);

    $phone = $xml->createElement('Phone', $customer['PhoneNumber']);
    $customerNode->appendChild($phone);

    $registrationDate = $xml->createElement('RegistrationDate', $customer['RegistrationDate']);
    $customerNode->appendChild($registrationDate);

    $root->appendChild($customerNode);
}

$xml->appendChild($root);

// Save XML file
$xmlFilePath = __DIR__ . '/customers.xml';
$xml->save($xmlFilePath);

// Apply XSL Transformation
$xslFilePath = __DIR__ . '/customerReport.xsl';
$transformer = new XSLTransformation();
$htmlContent = $transformer->transformWithXsl($xmlFilePath, $xslFilePath, $currentDateTime);

// Output HTML
echo $htmlContent;
?>
