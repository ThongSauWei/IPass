<?php
require_once '../models/User.php';
require_once '../core/SessionManager.php'; 
/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

class PassRecoveryController {
    public function handlePasswordRecovery() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];

            //call method
            $userModel = new User();
            $response = $userModel->passwordRecovery($email);

            //output the response success or fail
            echo $response;
        }
    }
}

// Assuming you're handling POST requests to this controller
$controller = new PassRecoveryController();
$controller->handlePasswordRecovery();