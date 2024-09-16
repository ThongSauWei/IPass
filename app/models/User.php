<?php

require_once __DIR__ . '/../core/NewModel.php';
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

class User extends NewModel {

    protected $table = 'user';

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

    public function findProfileImageByUserID($userID) {
        $result = $this->findAll()
                ->where('UserID', $userID)
                ->limit(1)
                ->execute();

        return !empty($result) ? $result[0]['ProfileImage'] : null;
    }

    public function register($data) {
        $data['Password'] = password_hash($data['Password'], PASSWORD_BCRYPT); //hash the password became hashvalue
        $this->insert($data)->execute();
    }

    public function login($identity, $password) {
        // Try to find the user by username first
        $resultByUsername = $this->findAll()
                ->where('Username', $identity)
                ->limit(1)
                ->execute();

        // If no user is found by username, check by email
        if (empty($resultByUsername)) {
            $resultByEmail = $this->findAll()
                    ->where('Email', $identity)
                    ->limit(1)
                    ->execute();

            // Check if any user was found by email
            if (!empty($resultByEmail)) {
                $user = $resultByEmail[0];
            } else {
                return false; // No user found by username or email
            }
        } else {
            $user = $resultByUsername[0];
        }

        // If the user was found, verify the password
        if (password_verify($password, $user['Password'])) {
            return $user; // Return user details if the password is correct
        }

        return false; // Return false if the password is incorrect
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

            error_log('Error generating UserID: ' . $e->getMessage());
            throw new Exception('Failed to generate UserID. Please try again later.');
        }
    }

}
