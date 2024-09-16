<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require_once __DIR__ . '/../facades/userFacade.php';
require_once '../views/Customer/register.php';

class UserController {

    private $userFacade;

    public function __construct() {
        $this->userFacade = new UserFacade();
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            
            $password = $_POST['password'];
            if(!$this->validPassword($password)){
                $errors[] = "Password must be at least 8 characters, with at least 1 uppercase letter, 1 lowercase letter, 1 special character, and 1 number.";
            }

            if ($_POST['password'] !== $_POST['confirm_password']) {
                $errors[] = "Passwords do not match!";
            }

            if ($this->userFacade->usernameExists($_POST['username'])) {
                $errors[] = "Username has already been taken. Please choose another one.";
            }

            if ($this->userFacade->emailExists($_POST['email'])) {
                $errors[] = "Email has already been used. Please choose another one.";
            }

            $userData = [
                'UserID' => $this->userFacade->generateUserID(),
                'Username' => $_POST['username'],
                'Email' => $_POST['email'],
                'Password' => $_POST['password'],
                'Role' => 'customer',
                'Birthday' => $_POST['birthday'],
                'Gender' => $_POST['gender'],
                'ProfileImage' => ''
            ];

            $customerData = [
                'CustomerID' => $this->userFacade->generateCustomerID(), // Generate unique CustomerID
                'UserID' => $userData['UserID'],
                'CustomerName' => $_POST['fullname'],
                'PhoneNumber' => $_POST['phone'],
                'Address' => '', // empty initially set in profile
                'Point' => 0
            ];

            if (empty($errors)) {
                try {
                    $this->userFacade->registerUser($userData, $customerData);
                    error_log('User registered successfully.');
                    // Redirect to login page after registration
                    header('Location: ../views/Customer/login.php');
                    exit();
                } catch (Exception $e) {
                    error_log('Error registering user: ' . $e->getMessage());
                    $error[] = "Registration failed. Please try again.";
                }
            }

            if (!empty($errors)) {
                session_start();
                $_SESSION['errors'] = $errors;

                // Redirect back to the registration form with errors
                header('Location: ../views/Customer/register.php');
                exit(); // Stop further execution after redirect
            } else {
                // Redirect to registration page if method is not POST
                require __DIR__ . '/../views/Customer/register.php';
                exit();
            }
        }
    }
    
    private function validPassword($password) {
        $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        return preg_match($pattern, $password);
    }

//    public function login() {
//        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//            $identity = $_POST['identity'];
//            $password = $_POST['password'];
//
//            $user = $this->userFacade->userLogin($identity, $password);
//
//            if ($user) {
//                session_start();
//                $_SESSION['user'] = $user;
//
//                // base on role navi
//
//                if ($user['Role'] === 'admin') {
//                    require '../views/Admin/dashboard.view.php';
//                } else {
//                    require '../views/Customer/homepage.view.php';
//                }
//                exit();
//            } else {
//                echo "Invalid login credentials.";
//            }
//        }
//    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        require '../views/Customer/login.php';
        exit();
    }

}
