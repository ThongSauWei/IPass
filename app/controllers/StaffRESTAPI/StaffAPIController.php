<?php

require_once __DIR__ . '/../../facades/userFacade.php';

class StaffAPIController {

    private $userFacade;

    public function __construct() {
        $this->userFacade = new UserFacade();
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'POST':
                $this->createStaff();
                break;
            case 'GET':
                $this->getAllStaff();
                break;
            case 'DELETE':
                $this->deleteStaff();
                break;
            case 'OPTIONS': // Handle preflight requests
                http_response_code(200);
                break;
            default:
                echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
                break;
        }
    }

    // Create a new staff member
    public function createStaff() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            echo json_encode(['status' => 'error', 'message' => 'No input data provided']);
            return;
        }

        // Validate input fields
        $errors = [];
        if ($this->userFacade->usernameExists($data['Username'])) {
            $errors[] = "Username already taken.";
        }

        if ($this->userFacade->emailExists($data['Email'])) {
            $errors[] = "Email already used.";
        }

        if ($data['Password'] !== $data['ConfirmPassword']) {
            $errors[] = "Passwords do not match.";
        }

        if (!empty($errors)) {
            echo json_encode(['status' => 'error', 'errors' => $errors]);
            return;
        }

        // Prepare user data (the staff being created)
        $staffData = [
            'UserID' => $this->userFacade->generateUserID(),
            'Username' => $data['Username'],
            'Email' => $data['Email'],
            'Password' => $data['Password'],
            'Birthday' => $data['Birthday'],
            'Gender' => $data['Gender'],
            'Role' => 'admin' // Ensure role is set to admin (staff)
        ];

        try {
            // Register the user and admin (staff)
            $this->userFacade->registerUser($staffData);

            // Success response
            echo json_encode(['status' => 'success', 'message' => 'Staff member created successfully.']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add staff member. Please try again.']);
        }
    }

    // Fetch all staff members
    public function getAllStaff() {
        try {
            $staffMembers = $this->userFacade->getAllStaff();
            echo json_encode(['status' => 'success', 'data' => $staffMembers]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to retrieve staff members.']);
        }
    }

    // Delete a staff member
    public function deleteStaff() {
        $userID = $_GET['id'] ?? null;

        if (!$userID) {
            echo json_encode(['status' => 'error', 'message' => 'No staff member ID provided']);
            return;
        }

        try {
            $result = $this->userFacade->deleteStaff($userID);

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Staff member deleted successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete staff member.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
}

$controller = new StaffAPIController();
$controller->handleRequest();
