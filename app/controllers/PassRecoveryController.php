<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../core/SessionManager.php';
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

class PassRecoveryController {

    public function handleRequest() {
        if (isset($_GET['action'])) {
            $action = $_GET['action'];

            switch ($action) {
                case 'recovery':
                    $this->PasswordRecovery();
                    break;

                case 'reset':
                    // Call the PasswordReset method without passing arguments
                    $this->PasswordReset();
                    break;

                default:
                    $this->invalidRequest('Invalid action specified.');
                    break;
            }
        } else {
            $this->invalidRequest('No action specified.');
        }
    }

    // Password recovery action
    public function PasswordRecovery() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = []; // Initialize error array

            $email = isset($_POST['email']) ? $_POST['email'] : null;

            // Validate email input
            if (empty($email)) {
                $errors[] = "Email is required.";
            }

            // If no input errors, proceed with password recovery
            if (empty($errors)) {
                $userModel = new User();
                $response = $userModel->passwordRecovery($email);

                // Decode the response to check for errors
                $responseData = json_decode($response, true);

                if ($responseData['status'] === 'error') {
                    $errors[] = $responseData['message'];
                } else {
                    // Success - redirect to login page with success message
                    $_SESSION['success'] = 'Password recovery email sent successfully. Check your email!';
                    header('Location: http://localhost/IPass/app/views/Customer/Login.php');
                    exit();
                }
            }

            // If errors, store them in session and redirect to PassRecoveryEmail.php
            if (!empty($errors)) {
                $_SESSION['error'] = $errors;
                header('Location: http://localhost/IPass/app/views/Customer/PassRecoverEmail.php');
                exit();
            }
        } else {
            $_SESSION['error'] = ['Unsupported request method.'];
            header('Location: http://localhost/IPass/app/views/Customer/PassRecoverEmail.php');
            exit();
        }
    }

    // Password reset action
    public function PasswordReset() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start(); // Start session to store errors
            $errors = []; // Initialize error array
            // Retrieve the token and new password from the request
            $token = isset($_GET['token']) ? $_GET['token'] : null;
            $newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : null;
            $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : null;

            // Validate token and password
            if (empty($token)) {
                $errors[] = "Invalid or missing token.";
            }

            if (empty($newPassword)) {
                $errors[] = "New password is required.";
            } elseif (!$this->validPassword($newPassword)) {
                $errors[] = "Password must be at least 8 characters, with at least 1 uppercase letter, 1 lowercase letter, 1 special character, and 1 number.";
            }

            if ($newPassword !== $confirmPassword) {
                $errors[] = "Passwords do not match!";
            }

            // If no input errors, proceed with password reset
            if (empty($errors)) {
                $userModel = new User();
                $response = $userModel->resetPassword($token, $newPassword);

                // Decode the response to check for errors
                $responseData = json_decode($response, true);

                if ($responseData['status'] === 'error') {
                    $errors[] = $responseData['message'];
                } else {
                    // Success - redirect to login page with success message
                    $_SESSION['success'] = 'Password has been updated successfully.';
                    header('Location: http://localhost/IPass/app/views/Customer/Login.php');
                    exit();
                }
            }

            // If errors, store them in session and redirect to the reset page
            if (!empty($errors)) {
                $_SESSION['error'] = $errors;
                header('Location: http://localhost/IPass/app/views/Customer/PassRecoveryNewPass.php?token=' . $token);
                exit();
            }
        } else {
            $_SESSION['error'] = ['Unsupported request method.'];
            header('Location: http://localhost/IPass/app/views/Customer/PassRecoveryNewPass.php');
            exit();
        }
    }

// Password validation method (same as in your registration process)
    private function validPassword($password) {
        $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        return preg_match($pattern, $password);
    }

    // Handle invalid requests
    private function invalidRequest($message) {
        $_SESSION['error'] = [$message];
        header('Location: http://localhost/IPass/app/views/Customer/PassRecoverEmail.php');
        exit();
    }

}

// Instantiate and handle the request
$controller = new PassRecoveryController();
$controller->handleRequest();

//    public function PasswordReset() {
//        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//            // Retrieve the token and new password from the request
//            $token = isset($_GET['token']) ? $_GET['token'] : null;
//            $newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : null;
//
//            if ($token && $newPassword) {
//                // Initialize the User model and call resetPassword() method
//                $userModel = new User();
//                $response = $userModel->resetPassword($token, $newPassword); // Call renamed method
//                // Return the response
//                header('Content-Type: application/json');
//                echo json_encode($response);
//            } else {
//                // Handle missing token or password
//                header('Content-Type: application/json');
//                echo json_encode([
//                    'status' => 'error',
//                    'message' => 'Token and new password are required.'
//                ]);
//            }
//        } else {
//            header('Content-Type: application/json');
//            echo json_encode([
//                'status' => 'error',
//                'message' => 'Unsupported request method.'
//            ]);
//        }
//    }
//
//    // Handle invalid requests
//    private function invalidRequest($message) {
//        header('Content-Type: application/json');
//        echo json_encode([
//            'status' => 'error',
//            'message' => $message
//        ]);
//    }
//
//}


//public function PasswordRecovery() {
//        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//            $email = isset($_POST['email']) ? $_POST['email'] : null;
//
//            if ($email) {
//                $userModel = new User();
//                $response = $userModel->passwordRecovery($email);
//                header('Content-Type: application/json');
//                echo $response;
//            } else {
//                header('Content-Type: application/json');
//                echo json_encode([
//                    'status' => 'error',
//                    'message' => 'Email is required.'
//                ]);
//            }
//        } else {
//            header('Content-Type: application/json');
//            echo json_encode([
//                'status' => 'error',
//                'message' => 'Unsupported request method.'
//            ]);
//        }
//    }