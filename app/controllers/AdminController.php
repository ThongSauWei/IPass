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
        $action = $_GET['action'] ?? 'displayStaff';

        switch ($action) {
            case 'deleteStaff':
                $this->deleteStaff();
                break;
            case 'editStaff':
                $this->editStaff();
                break;
            default:
                $this->displayStaff();
                break;
        }
    }
    
     public function displayStaff() {
        try {
            SessionManager::startSession();
            $staffMembers = $this->userFacade->getAllStaff();
            $_SESSION['staffMembers'] = $staffMembers; // Store staff members in session
            require_once __DIR__ . '/IPass/app/views/Admin/User/displayStaff.php';
        } catch (Exception $e) {
            echo "Failed to load staff data. Please try again later.";
        }
    }
    
    //delete staff
    public function deleteStaff() {
        SessionManager::startSession();
        $errors = [];

        if (isset($_GET['id'])) {//get userid
            $userID = $_GET['id'];

            try {
                if ($this->userFacade->deleteStaff($userID)) {
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

        if (!empty($errors)) {
            $_SESSION['error'] = $errors; // Convert error array into a string for displaying
        }

        //back to the staff list after delete
        header('Location: /IPass/app/views/Admin/User/displayStaff.php');
        exit();
    }
    
    public function editStaff() {
        SessionManager::startSession();
        
        //fecth staff data base on id
        if(isset($_GET['id'])) {
            $userID = $_GET['id'];
            $staff = $this->userFacade->staffSelected($userID);//get the userID
            
            if($staff) {
                $_SESSION['staff'] = $staff;
                header('Location: /IPass/app/views/Admin/User/editStaff.php');
                exit();
            } else {
                $_SESSION['error'] = "Staff number (UserID; $userID) not found.";
            }
        } else {
            $_SESSION['error'] = "No staff member selected for editing.";
        }
        
         header('Location: ./IPass/app/views/Admin/User/displayStaff.php');
        exit();
    }

}

$adminController = new AdminController();
$adminController->handleRequest();