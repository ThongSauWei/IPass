<?php


require_once __DIR__ . '/../core/SessionManager.php';
require_once __DIR__ . '/../facades/userFacade.php';

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

class ProfileController {

    private $userFacade;

    public function __construct() {
        $this->userFacade = new UserFacade();
    }

    public function handleRequest() { //try to get the action from the query string such as ?action=update form there
        $action = isset($_GET['action']) ? $_GET['action'] : 'view';

        switch ($action) {
            case 'update' :
                $this->updateProfile();
                break;
            default :
                require_once __DIR__ . './_404.php';
                break;
        }
    }

    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            SessionManager::startSession();
            $errors = [];

            $user = SessionManager::getUser();
            $userID = $user['UserID'];

            // Validate phone number
            $phonePattern = "/^(\+?6?01)[0|1|2|3|4|6|7|8|9]\-*[0-9]{7,8}$/";
            if (!preg_match($phonePattern, $_POST['phone'])) {
                $errors[] = "Invalid phone number format. Please use a valid Malaysian phone number.";
            }

            $userData = [
                'Birthday' => $_POST['birthday'],
                'Gender' => $_POST['gender'],
            ];

            $customerData = [
                'CustomerName' => $_POST['fullname'],
                'PhoneNumber' => $_POST['phone'],
                'Address' => $_POST['address'],
            ];

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

            if (empty($errors)) {
                // Update the user and customer profiles
                $this->userFacade->updateProfile($userID, $userData, $customerData);

                // Update session with the new profile image, if available
                if (isset($userData['ProfileImage'])) {
                    $_SESSION['user']['ProfileImage'] = $userData['ProfileImage'];
                }
                header('Location: ../views/Customer/profile.php');
                exit();
            } else {
                $_SESSION['errors'] = $errors;
                require_once __DIR__ . '/../views/Customer/profile.php';
                exit();
            }
        }
    }

}

$profileController = new ProfileController();
$profileController->handleRequest();
