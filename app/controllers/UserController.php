<?php

require_once __DIR__ . '/../core/SessionManager.php';
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */


class UserController {

    private $userFacade;

    public function __construct() {
        require_once __DIR__ . '/../facades/userFacade.php';
        $this->userFacade = new UserFacade();
    }

    public function handleRequest() {
        // Get the 'action' from the query string (e.g., ?action=register)
        $action = isset($_GET['action']) ? $_GET['action'] : '';

        switch ($action) {
            case 'register':
                $this->register();
                break;
            case 'login':
                $this->login();
                break;
            case 'logout':
                $this->logout();
                break;
            default:
                //show the form or a 404 page
                require_once __DIR__ . './_404.php';
                break;
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];

            if ($this->userFacade->usernameExists($_POST['username'])) {
                $errors[] = "Username has already been taken. Please choose another one.";
            }

            if ($this->userFacade->emailExists($_POST['email'])) {
                $errors[] = "Email has already been used. Please choose another one.";
            }

            $phone = $_POST['phone']; //^(\+?6?01)[0|1|2|3|4|6|7|8|9]\-*[0-9]{7,8}$
            if (!$this->validPhoneNum($phone)) {
                $errors[] = "Invalid Malaysian phone number. Eg.'0123456789'";
            }

            $password = $_POST['password'];
            if (!$this->validPassword($password)) {
                error_log("Password validation failed.");
                $errors[] = "Password must be at least 8 characters, with at least 1 uppercase letter, 1 lowercase letter, 1 special character, and 1 number.";
            }

            if ($_POST['password'] !== $_POST['confirmPass']) {
                $errors[] = "Passwords do not match!";
            }



            if (empty($errors)) {

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
            }

            if (!empty($errors)) {
                session_start();
                $_SESSION['errors'] = $errors;

                // Redirect back to the registration form with errors
                header('Location: ../views/Customer/register.php');
                exit(); // Stop further execution after redirect
            } else {
                require_once __DIR__ . '/../views/Customer/register.php';
                exit();
            }
        }
    }

    private function validPhoneNum($phone) {
        // Define the regex pattern for Malaysian phone numbers
        $pattern = "/^(\+?6?01)[0|1|2|3|4|6|7|8|9]\-*[0-9]{7,8}$/";
         //^ = start, (\+?6?01) can be start with 60 or 0, [0|1|2|3|4|6|7|8|9] start with 011,012 or.. but no 5, \-*[0-9] can be repeat number many time, $ end
        
        return preg_match($pattern, $phone);
    }

    private function validPassword($password) {
        $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        return preg_match($pattern, $password);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start(); // Start the session to store errors
            $errors = [];

            $identity = $_POST['identity'];
            $password = $_POST['password'];

            // Validate input fields
            if (empty($identity)) {
                $errors[] = "Username or Email is required.";
            }

            if (empty($password)) {
                $errors[] = "Password is required.";
            }

            // Proceed with login if no input errors
            if (empty($errors)) {
                // Try to login the user via facade
                $user = $this->userFacade->userLogin($identity, $password);

                if ($user) {
                    // Check if the password matches the hashed password in the database
                    if (password_verify($password, $user['Password'])) {
                        // Store UserID and Role in the session after successful login
                        SessionManager::loginUser([
                            'UserID' => $user['UserID'],
                            'Email' => $user['Email'],
                            'Username' => $user['Username'],
                            'Role' => $user['Role'],
                            'Birthday' => $user['Birthday']
                        ]);

                        // Redirect based on the user role
                        if ($user['Role'] === 'admin') {
                            header('Location: ../views/Admin/dashboard.view.php');
                        } else {
                            header('Location: ../views/Customer/homepage.view.php');
                        }
                        exit();
                    } else {
                        // Password mismatch error
                        $errors[] = "Invalid login credentials. Please check your password.";
                    }
                } else {
                    // Set an error for invalid credentials
                    $errors[] = "Invalid login credentials. Please check your username or email.";
                }
            }

            // If there are any errors, store them in the session and redirect
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                header('Location: ../views/Customer/login.php'); // Redirect back to login page
                exit();
            }
        }
    }

    public function logout() {
        SessionManager::logout(); // Call the logout function in SessionManager
        header('Location: ../views/Customer/login.php'); // Redirect to the login page after logout
        exit();
    }

}

$userController = new UserController();
$userController->handleRequest();
