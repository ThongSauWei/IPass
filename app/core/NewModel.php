<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of NewModel
 *
 * @author User
 */

require_once 'NewDatabase.php';

class NewModel {
    private $db;
    protected $table;
    private $sql = "";
    private $binding = [];
    
    public function __construct() {
        $this->db = NewDatabase::getInstance();
    }
    
    public function findAll() {
        $this->sql .= "SELECT * from $this->table";
        return $this;
    }
    
    public function where($column, $value) {
        if (strpos($this->sql, "WHERE") === false) {
            $this->sql .= " WHERE $column = :$column";
        } else {
            $this->sql .= " AND $column = :$column";
        }
        $this->binding[$column] = $value;
        
        return $this;
    }
    
    public function execute() {
        return $this->db->query($this->sql, $this->binding);
    }
}
