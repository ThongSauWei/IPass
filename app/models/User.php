<?php

require_once __DIR__ . '/../core/NewModel.php';
/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

class User extends NewModel {
    protected $table = 'user';
    
    public function findByUsername($username){
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
    
    public function register($data){
        $data['Password'] = password_hash($data['Password'], PASSWORD_BCRYPT);//hash the password became hashvalue
        $this->insert($data)->execute();
    }
    
    public function login($identity, $password){
        return $this->findAll()
                ->where('Username', $identity)
                ->where('Email', $identity, 'OR')//manually change to OR else the default will be AND email and username both needed
                ->execute();
        
        //if email or username exist then check password
        if($user && password_verify($password, $user['Password'])){
            return user; //if pass correct return user
        }
        
        return false;//else false
    }
    
    public function generateUserID() {
        $char = 'U';
        $length = 4; //0000

        try {
            // last id num
            $result = $this->findAll()
                    ->orderBy('UserID', $DESC)//the large num
                    ->limit(1) //make sure only 1 row return
                    ->execute();
            //then get the last id
            $lastID = !empty($result) ? $result[0]['UserID'] : null;

            //determine next ID
            if ($lastID) {
                //take last num use intval convert 0001 to 1
                $lastNum = intval(substr($lastID, strlen($prefix))); //substr extracts a portion of a string, strlen return length
                //then +1
                $nextNum = $lastNumber + 1;
            } else {
                $nextNum = 1;
            }

            // let the next id start with 0
            $nextID = $prefix . str_pad($nextNum, $length, '0' . STR_PAD_LEFT);

            return $nextID;
        } catch (Exception $e) {

            error_log('Error generating UserID: ' . $e->getMessage());
            throw new Exception('Failed to generate UserID. Please try again later.');
        }
    }
    
//    // Generate a unique User ID
//    public function generateUserID() {
//        $lastUser = $this->findAll()
//            ->orderBy('UserID', 'DESC')
//            ->limit(1)
//            ->execute();
//
//        $nextID = $lastUser ? intval(substr($lastUser['UserID'], 1)) + 1 : 1;
//        return 'U' . str_pad($nextID, 4, '0', STR_PAD_LEFT);
//    }
//    

    
}