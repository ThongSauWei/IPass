<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require_once '../facades/userFacade.php';

class UserController {

    private $userFacade;

    public function __construct() {
        $this->userFacade = new UserFacade();
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'UserID' => $this->userFacade->generateUserID(),
                'Username' => $_POST['username'],
                'Email' => $_POST[email],
                'Password' => $_POST['password'],
                'Role' => 'customer',
                'Birthday' => $_POST['birthday'],
                'Gender' => $_POST['gender'],
                'ProfileImage' => $_POST['profileImage']
            ];

            $customerData = [
                'CustomerID' => $this->userFacade->generateCustomerID(), // Generate unique CustomerID
                'UserID' => $userData['UserID'],
                'CustomerName' => $_POST['fullname'],
                'PhoneNumber' => $_POST['phone'],
                'Address' => '', // empty initially set in profile
                'Point' => 0
            ];

            $this->userFacade->registerUser($userData, $customerData);

            // Redirect to login page after registration
            require '../views/Customer/login.php';
            exit();
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identity = $_POST['identity'];
            $password = $_POST['password'];

            $user = $this->userFacade->userLogin($identity, $password);

            if ($user) {
                session_start();
                $_SESSION['user'] = $user;

                // base on role navi

                if ($user['Role'] === 'admin') {
                    require '../views/Admin/dashboard.view.php';
                } else {
                    require '../views/Customer/homepage.view.php';
                }
                exit();
            } else {
                echo "Invalid login credentials.";
            }
        }
    }

}
