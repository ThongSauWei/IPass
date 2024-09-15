<?php

require_once '../core/NewModel.php';
/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

class User extends NewModel {
    protected $table = 'User';
    
    //search User by ID
    public function findById($userID) {
        return $this->findAll()->where('UserID', $userID)->execute();
    }
    
    public function verifyPassword($inputPass) {
        $storedHash = $this->findAll()->where('UserID', $this->UserID)->execute()[0]['Password'] ?? null;
        if ($storedHash === null) {
            return false;
        }
        return password_verify($inputPass, $storedHash);
    }
    
    //convert pass to hashvalue
    public function convertPassword($hashPass){
        $newHash = password_hash($hashPass, PASSWORD_BCRYPT);
        $this->update($this->UserID, ['Password' => $newHash]);
    }
}