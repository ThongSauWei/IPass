<?php

require_once __DIR__ . '/../core/NewModel.php';
<<<<<<< HEAD
require_once __DIR__ . '/../web/sendGridService.php';
=======
>>>>>>> parent of d2fbf34 (not complete)
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

class User extends NewModel {

    protected $table = 'user';

    //get data part
<<<<<<< HEAD
=======

>>>>>>> parent of d2fbf34 (not complete)
    public function findUserByUserID($userID) {
        $result = $this->findAll()
                ->where('UserID', $userID)
                ->limit(1)
                ->execute();

        if (!empty($result)) {
            return $result[0];
        }
        return null; // Return null if no user found
    }

    public function findByUsername($username) {
        $result = $this->findAll()
                ->where('Username', $username)
                ->execute();

        return !empty($result); //return true if the username exists
    }

    public function findByEmail($email) {
        $result = $this->findAll()
                ->where('Email', $email)
                ->execute();

        return !empty($result); //return true if the email exist
    }

    public function getUserBirthday($userID) {
        // Fetch the user's birthday based on their UserID
        $result = $this->findAll()
                ->where('UserID', $userID)
                ->limit(1)
                ->execute();

        if (!empty($result)) {
            return $result[0]['Birthday']; // Return the birthday if found
        } else {
            return null; // If no birthday found, return null
        }
    }

    //get the profileImage
    public function getUserProfileImage($userID) {
        $result = $this->findAll()
                ->where('UserID', $userID)
                ->limit(1)
                ->execute();

        return $result[0]['ProfileImage'] ?? null; // Returns the profile image path or null
    }

    //get user gender
    public function getUserGender($userID) {
        $result = $this->findAll()
                ->where('UserID', $userID)
                ->limit(1)
                ->execute();

        return $result[0]['Gender'] ?? null; // Returns 'male', 'female', or 'other' as stored
    }

    //profile part
    public function updateProfile($userID, $userData) {
        foreach ($userData as $column => $value) {
            $this->update($column, $value); // Call update() for each column-value pair
        }

        //target the specific user
        $this->where('UserID', $userID);

        return $this->execute();
    }

    //login register part
    public function register($data) {
        $data['Password'] = password_hash($data['Password'], PASSWORD_BCRYPT); //hash the password became hashvalue
        $this->insert($data)->execute();
    }

    public function login($identity, $password) {
        //find the user by username or email
        $resultByUsername = $this->findAll()
                ->where('Username', $identity)
                ->limit(1)
                ->execute();

        if (empty($resultByUsername)) {
            $resultByEmail = $this->findAll()
                    ->where('Email', $identity)
                    ->limit(1)
                    ->execute();

            if (!empty($resultByEmail)) {
                $user = $resultByEmail[0];
            } else {
                return false; //no user found by username or email
            }
        } else {
            $user = $resultByUsername[0];
        }

        // Check if the user is active
        if ($user['isActive'] != 1) { //user 1 is active if not equal to 1 deactive
            return ['error' => 'inactive']; //inactive
        }

        //if the user was found, verify the password
        if (password_verify($password, $user['Password'])) {
            return $user; //return user details if the password is correct and the user is active
        }

        return false; //return false if the password is incorrect
    }

    public function generateUserID() {
        $char = 'U';
        $length = 4; //0000

        try {
            // last id num
            $result = $this->findAll()
                    ->orderBy('UserID', 'DESC')//the large num
                    ->limit(1) //make sure only 1 row return
                    ->execute();
            //then get the last id if no record set null
            $lastID = !empty($result) ? $result[0]['UserID'] : null;

            //determine next ID
            if ($lastID) {
                //take last num use intval convert 0001 to 1
                $lastNum = intval(substr($lastID, strlen($char))); //substr extracts a portion of a string, strlen return length
                //then +1
                $nextNum = $lastNum + 1;
            } else {
                $nextNum = 1;
            }

            // the next UserID with the required format 'U0001'
            $nextID = $char . str_pad($nextNum, $length, '0', STR_PAD_LEFT);

            return $nextID;
        } catch (Exception $e) {
            throw new Exception('Failed to generate UserID.');
        }
    }

    public function updateStatus($userID, $status) {
        return $this->update('isActive', $status)
                        ->where('UserID', $userID)
                        ->execute();
    }

<<<<<<< HEAD
    //password recovery
    public function passwordRecovery($email) {
        // 1. Check if the email exists in the database
        $user = $this->findAll()
                ->where('Email', $email)
                ->limit(1)
                ->execute(); // Correct chaining order

        if (!empty($user)) {
            // 2. Generate a recovery token
            $token = bin2hex(random_bytes(50));

            // 3. Get current time in Kuala Lumpur and set the expiry time
            $kualaLumpurTime = new DateTime('now', new DateTimeZone('Asia/Kuala_Lumpur'));
            $kualaLumpurTime->modify('+1 hour'); // Add 1 hour for token expiry
            $expiry = $kualaLumpurTime->format('Y-m-d H:i:s'); // Format it for MySQL
            // Debugging: Print the expiry time for verification
            echo "Token Expiry (Kuala Lumpur Time): " . $expiry . "<br>";

            // 4. Update the user with the recovery token and expiry time (one by one)
            $this->update('recovery_token', $token)
                    ->where('Email', $email)->execute(); // Update the token
            $this->update('token_expiry', $expiry)
                    ->where('Email', $email)->execute(); // Update the expiry time
            // 5. Send the recovery email
            $result = sendPasswordRecoveryEmail($email, $token);  // Call SendGrid service to send email

            if ($result == 202) {
                return json_encode([
                    'status' => 'success',
                    'message' => 'Password recovery email sent successfully.'
                ]);
            } else {
                return json_encode([
                    'status' => 'error',
                    'message' => 'Failed to send recovery email.'
                ]);
            }
        } else {
            return json_encode([
                'status' => 'error',
                'message' => 'Email not found.'
            ]);
        }
    }

    public function resetPassword($token, $newPassword) {
        // 1. Get current date and time in Kuala Lumpur timezone
        $kualaLumpurTime = new DateTime('now', new DateTimeZone('Asia/Kuala_Lumpur'));
        $currentDateTime = $kualaLumpurTime->format('Y-m-d H:i:s'); // Format the date and time as a string
        // Debugging: Print the current time and token
        echo "Current Kuala Lumpur Time: " . $currentDateTime . "<br>";
        echo "Token from URL: " . $token . "<br>";

        // 2. Check if the token is valid and not expired
        $user = $this->findAll()
                ->where('recovery_token', $token)
                ->where('token_expiry', $currentDateTime, '>') // Compare against current Kuala Lumpur date and time
                ->execute();

        // Debugging: Check if a user was found
        if (empty($user)) {
            echo "Token not found or expired.<br>";
            return [
                'status' => 'error',
                'message' => 'Invalid or expired token.'
            ];
        } else {
            echo "User found. Proceeding with password reset.<br>";
        }

        // 3. Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // 4. Update the user's password and clear the token (update one by one)
        $this->update('Password', $hashedPassword)
                ->where('recovery_token', $token)->execute(); // Update the password
        $this->update('recovery_token', null)
                ->where('recovery_token', $token)->execute(); // Clear the token
        $this->update('token_expiry', null)
                ->where('recovery_token', $token)->execute(); // Clear the expiry time

        return [
            'status' => 'success',
            'message' => 'Password has been updated successfully.'
        ];
    }

=======
>>>>>>> parent of d2fbf34 (not complete)
}
