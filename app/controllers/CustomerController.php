<?php

$configPath = dirname(__DIR__) . '/core/config.php';
if (file_exists($configPath)) {
    include_once $configPath;
} else {
    echo 'Config file not found: ' . $configPath;
}

require_once __DIR__ . '/../core/SessionManager.php';
require_once __DIR__ . '/../facades/UserFacade.php';

class CustomerController {

    private $userFacade;

    public function __construct() {
        $this->userFacade = new UserFacade();
    }

    public function handleRequest() {
        $action = $_GET['action'] ?? 'displayCustomer';

        switch ($action) {
            case 'deleteCustomer':
                $this->deleteCustomer();
                break;
            case 'detailCustomer':
                $this->detailCustomer();
                break;
            case 'toggleStatus':
                $this->toggleStatus();
                break;
            default:
                $this->displayCustomer();
                break;
        }
    }

    //display customer
    public function displayCustomer() {
        try {
            SessionManager::startSession();

            $customers = $this->userFacade->getAllCustomers();

            $_SESSION['customers'] = $customers; //store customer details in session
            require_once __DIR__ . '/../views/Admin/User/displayCustomer.php';
        } catch (Exception $e) {
            throw "Failed to load customer data. Please try again later.";
        }
    }

    //delete customer
    public function deleteCustomer() {
        SessionManager::startSession();  // Start session for storing success/error messages
        $errors = [];

        if (isset($_GET['id'])) { // Get UserID from the URL
            $userID = $_GET['id'];

            try {
                // Call the facade to delete the customer
                $result = $this->userFacade->deleteCustomer($userID);

                // If deletion was successful
                if ($result) {
                    $_SESSION['success'] = "Customer (UserID: $userID) deleted successfully.";
                } else {
                    $errors[] = "Failed to delete customer (UserID: $userID).";
                }
            } catch (Exception $e) {
                $errors[] = "Error occurred: " . $e->getMessage();
            }
        } else {
            $errors[] = "No customer selected for deletion.";
        }

        // Handle errors
        if (!empty($errors)) {
            $_SESSION['error'] = $errors;
        }

        // Redirect back to the customer list after deletion
        header('Location: http://localhost/IPass/app/views/Admin/User/displayCustomer.php');
        exit();
    }

    public function detailCustomer() {
        SessionManager::startSession();

        // Debug the GET array
        if (isset($_GET['id'])) {
            $userID = $_GET['id'];

            try {
                $customerDetails = $this->userFacade->customerSelected($userID);
                if ($customerDetails) {
                    $_SESSION['customer'] = $customerDetails;
                    header('Location: http://localhost/IPass/app/views/Admin/User/detailCustomer.php');
                    exit();
                } else {
                    $_SESSION['error'] = "Customer (UserID: $userID) not found.";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "An error occurred: " . $e->getMessage();
            }
        } else {
            $_SESSION['error'] = "No customer selected.";
        }

        header('Location: http://localhost/IPass/app/views/Admin/User/displayCustomer.php');
        exit();
    }

    public function toggleStatus() {
        SessionManager::startSession();

        if (isset($_GET['id'])) {
            $userID = $_GET['id'];
            $customerDetails = $this->userFacade->getUserDetails($userID);

            if ($customerDetails) {
                // Toggle status
                $newStatus = $customerDetails['isActive'] ? 0 : 1;
                $this->userFacade->updateUserStatus($userID, $newStatus);
                $_SESSION['success'] = "Customer status updated successfully.";
            } else {
                $_SESSION['error'] = "Customer not found.";
            }
        } else {
            $_SESSION['error'] = "No customer selected.";
        }

        header('Location: http://localhost/IPass/app/views/Admin/User/displayCustomer.php');
        exit();
    }

}

$customerController = new CustomerController();
$customerController->handleRequest();
