<?php
// Load the required models from the correct path
require_once __DIR__ . '/../../../../models/User.php';
require_once __DIR__ . '/../../../../models/Customer.php';

// Assume you have a Customer class that queries the DB
$customerModel = new Customer();
$customers = $customerModel->displayAllCustomer(); // Fetch data from DB

// Create a new XML document
$xml = new DOMDocument('1.0', 'UTF-8');
$root = $xml->createElement('customers');

// Loop through the database results and build the XML structure
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

    $gender = $xml->createElement('Gender', $customer['Gender']);
    $customerNode->appendChild($gender);

    $phone = $xml->createElement('Phone', $customer['PhoneNumber']);
    $customerNode->appendChild($phone);

    $registrationDate = $xml->createElement('RegistrationDate', $customer['RegistrationDate']);
    $customerNode->appendChild($registrationDate);

    // Add the customer node to the root (customers)
    $root->appendChild($customerNode);
}

// Add the root node to the document
$xml->appendChild($root);

// Save the XML to a file
$xml->save(__DIR__ . '/customers.xml'); // Save the generated XML

// Optional: Notify when the file has been successfully generated
echo "XML generated successfully!";
