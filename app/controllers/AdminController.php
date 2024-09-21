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
            case 'addStaff':
                $this->addStaff();
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

    //add staff
    public function addStaff() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];

            // Validate the input fields
            if ($this->userFacade->usernameExists($_POST['username'])) {
                $errors[] = "Username has already been taken. Please choose another one.";
            }

            if ($this->userFacade->emailExists($_POST['email'])) {
                $errors[] = "Email has already been used. Please choose another one.";
            }
            
            $password = $_POST['password'];
            if (!$this->validPassword($password)) {
                $errors[] = "Password must be at least 8 characters, with at least 1 uppercase letter, 1 lowercase letter, 1 special character, and 1 number.";
            }

            // Validate password
            if ($_POST['password'] !== $_POST['confirmPass']) {
                $errors[] = "Passwords do not match.";
            }

            // Proceed if no errors
            if (empty($errors)) {
                $userData = [
                    'UserID' => $this->userFacade->generateUserID(),
                    'Username' => $_POST['username'],
                    'Email' => $_POST['email'],
                    'Password' => $_POST['password'],
                    'Role' => 'admin', // Staff is an admin role
                    'Birthday' => $_POST['birthday'],
                    'Gender' => $_POST['gender'],
                    'ProfileImage' => ''
                ];

                // Handle profile image upload
                if (isset($_FILES['profileImage']['tmp_name']) && !empty($_FILES['profileImage']['tmp_name'])) {
                    $imgDIR = __DIR__ . '/../../public/assets/img/ProfileImage/';
                    $imgFile = $imgDIR . basename($_FILES['profileImage']['name']);
                    $imageFileType = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION));

                    // Handle profile image upload
                    if (isset($_FILES['profileImage']['tmp_name']) && $_FILES['profileImage']['tmp_name']) {
                        $imgDIR = __DIR__ . '/../../public/assets/img/ProfileImage/';
                        $imgFile = $imgDIR . basename($_FILES['profileImage']['name']);
                        $imageFileType = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION));

                        // Check if the uploaded file is an image
                        $check = getimagesize($_FILES['profileImage']['tmp_name']);
                        if ($check !== false) {
                            if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $imgFile)) {
                                $userData['ProfileImage'] = '/assets/img/ProfileImage/' . basename($_FILES['profileImage']['name']);
                            } else {
                                $errors[] = "Failed to upload the image, please try again.";
                            }
                        } else {
                            $errors[] = "The file is not a valid image.";
                        }
                    }
                }

                try {
                    // Register the user and admin (staff) in the system
                    $this->userFacade->registerUser($userData);

                    // Success message and redirect to detailStaff page
                    $_SESSION['success'] = "Staff member added successfully.";
                    header('Location: /IPass/app/controllers/AdminController.php?action=detailStaff&id=' . $userData['UserID']);
                    exit();
                } catch (Exception $e) {
                    $errors[] = "Failed to add staff. Please try again.";
                }
            }

            // If there are errors, store them in session and redirect back to add staff form
            if (!empty($errors)) {
                $_SESSION['error'] = $errors;
                header('Location: /IPass/app/views/Admin/User/addStaff.php');
                exit();
            }
        }
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
    
    private function validPassword($password) {
        $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        return preg_match($pattern, $password);
    }

}

$adminController = new AdminController();
$adminController->handleRequest();
