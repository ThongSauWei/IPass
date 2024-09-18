<?php

$configPath = dirname(__DIR__) . '/core/config.php';
if (file_exists($configPath)) {
    include_once $configPath;
} else {
    echo 'Config file not found: ' . $configPath;
}

require_once __DIR__ . '/../core/SessionManager.php';
require_once __DIR__ . '/../facades/userFacade.php';

class AdminController {

    private $userFacade;

    public function __construct() {
        $this->userFacade = new UserFacade();
    }

    public function handleRequest() {
        $action = $_GET['action'] ?? 'displayStaff';  // Default to 'displayStaff' if no action is provided

        switch ($action) {
            case 'deleteStaff':
                $this->deleteStaff();  // Call the deleteStaff method
                break;
            case 'detailStaff':
                $this->detailStaff();  // Call the editStaff method
                break;
            case 'toggleStatus':
                $this->toggleStatus();
                break;
            default:
                $this->displayStaff();  // Default action is to display the staff list
                break;
        }
    }

    public function displayStaff() {
        try {
            SessionManager::startSession();
            $staffMembers = $this->userFacade->getAllStaff();
            $_SESSION['staffMembers'] = $staffMembers; // Store staff members in session
            require_once __DIR__ . '/../views/Admin/User/displayStaff.php';
        } catch (Exception $e) {
            echo "Failed to load staff data. Please try again later.";
        }
    }

    //delete staff
    public function deleteStaff() {
        SessionManager::startSession();  // Start session for storing success/error messages
        $errors = [];

        if (isset($_GET['id'])) { // Get UserID from the URL
            $userID = $_GET['id'];

            try {
                // Call the facade to delete the staff
                $result = $this->userFacade->deleteStaff($userID);

                // If deletion was successful
                if ($result) {
                    $_SESSION['success'] = "Staff member (UserID: $userID) deleted successfully.";
                } else {
                    $errors[] = "Failed to delete staff member (UserID: $userID).";
                }
            } catch (Exception $e) {
                $errors[] = "Error occurred: " . $e->getMessage();
            }
        } else {
            $errors[] = "No staff member selected for deletion.";
        }

        // Handle errors
        if (!empty($errors)) {
            $_SESSION['error'] = $errors;
        }

        // Redirect back to the staff list after deletion
        header('Location: /IPass/app/views/Admin/User/displayStaff.php');
        exit();
    }

    public function toggleStatus() {
        SessionManager::startSession();

        if (isset($_GET['id'])) {
            $userID = $_GET['id'];

            // Fetch user details
            $userDetails = $this->userFacade->getUserDetails($userID);

            if ($userDetails) {
                // Toggle the active status
                $newStatus = $userDetails['isActive'] ? 0 : 1;
                $this->userFacade->updateUserStatus($userID, $newStatus);
                $_SESSION['success'] = "User status updated successfully.";
            } else {
                $_SESSION['error'] = "User not found.";
            }
        } else {
            $_SESSION['error'] = "No user selected.";
        }

        header('Location: /IPass/app/views/Admin/User/displayStaff.php');
        exit();
    }

    public function detailStaff() {
        SessionManager::startSession();

        if (isset($_GET['id'])) {
            $userID = $_GET['id'];

            try {
                $staffDetails = $this->userFacade->staffSelected($userID);
                if ($staffDetails) {
                    $_SESSION['staff'] = $staffDetails;

                    header('Location: /IPass/app/views/Admin/User/detailStaff.php');
                    exit();
                } else {
                    $_SESSION['error'] = "Staff member (UserID: $userID) not found.";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "An error occurred: " . $e->getMessage();
            }
        } else {
            $_SESSION['error'] = "No staff member selected.";
        }

        header('Location: /IPass/app/views/Admin/User/displayStaff.php');
        exit();
    }

}

$adminController = new AdminController();
$adminController->handleRequest();
