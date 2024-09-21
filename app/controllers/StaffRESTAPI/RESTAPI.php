<?php
require_once __DIR__ . '/StaffAPIController.php';


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$staffAPI = new StaffAPIController();

// Handle request methods
$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'POST') {
    // Create staff
    $data = json_decode(file_get_contents("php://input"), true);
    $response = $staffAPI->createStaff($data);
    echo json_encode($response);
} elseif ($method == 'DELETE') {
    // Delete staff
    $id = $_GET['id'];
    $response = $staffAPI->deleteStaff($id);
    echo json_encode($response);
} elseif ($method == 'GET') {
    // Retrieve all staff
    $staff = $staffAPI->getAllStaff();
    echo json_encode($staff);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}