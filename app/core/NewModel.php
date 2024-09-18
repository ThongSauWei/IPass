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
    const EQUAL = '=';
    const NOT_EQUAL = '!=';
    const LIKE = 'LIKE';
    const GREATER_THAN = '>';
    const GREATER_THAN_OR_EQUAL_TO = '>=';
    const LESS_THAN = '<';
    const LESS_THAN_OR_EQUAL_TO = '<=';

    private $db;
    protected $table;
    private $binding = [];
    
    private $stmts = [
        "startingStmt" => "",
        "whereStmt" => "",
        "orderStmt" => "",
        "limitStmt" => "",
        "offsetStmt" => ""
    ];
    
    private $queryType = null;
    
    public function __construct() {
        $this->db = NewDatabase::getInstance();
    }
    
    public function findAll($duplicateCol = []) {
        if ($this->queryType === null) {
            $this->queryType = "Select";
            $columns = "";
            if ($duplicateCol === []) {
                $columns = '*';
            } else {
                $columns = "DISTINCT " . implode(', ', $duplicateCol);
            }
            
            $this->stmts["startingStmt"] = "SELECT $columns from $this->table";
        } else {
            throw new Exception("Cannot chain findAll() after a similar operation is called");
        }
        
        return $this;
    }
    
    public function insert($data) {
        if ($this->queryType === null) {
            $this->queryType = "Insert";
            
            $keys = [];
            
            if (is_array($data)) {
                $keys = array_keys($data);
                
                $this->binding = $data;
            } else {
                $class = new ReflectionClass($data);
                $properties = $class->getProperties();
                
                foreach ($properties as $property) {
                    $column = $property->getName();
                    $value = $property->getValue($data);
                    
                    $keys[] = $column;
                    
                    $this->binding[$column] = $value;
                }
            }
            
            $attrStmt = implode(", ", $keys);
            $valueStmt = ":" . implode(", :", $keys);
            
            $this->stmts["startingStmt"] = "INSERT INTO $this->table ($attrStmt) VALUES ($valueStmt)";
        } else {
            throw new Exception("Cannot chain insert() after a similar operation is called");
        }
        
        return $this;
    }
    
    public function update($column, $newValue) {
        if ($this->queryType === null) {
            $this->queryType = "Update";
            $this->stmts["startingStmt"] = "UPDATE $this->table SET $column = :$column";
        } else if ($this->queryType === "Update") {
            $this->stmts["startingStmt"] .= ", $column = :$column";
        } else {
            throw new Exception("Cannot chain update() after a similar operation is called");
        }
        
        $this->binding[$column] = $newValue;
        
        return $this;
    }
    
    public function delete() {
        if ($this->queryType === null) {
            $this->queryType = "Delete";
            $this->stmts["startingStmt"] = "DELETE FROM $this->table";
        } else {
            throw new Exception("Cannot chain delete() after a similar operation is called");
        }
        
        return $this;
    }
    
    public function where($column, $value, $type = NewModel::EQUAL) {
        if ($this->queryType === "Insert") {
            throw new Exception("where() cannot be called in an insert query");
        } else if ($this->queryType === null) {
            throw new Exception("You must specify an operation (e.g. findAll(), insert(), update(), delete()) before executing a query");
        }
        
        if (strpos($this->stmts["whereStmt"], "WHERE") === false) {
            $this->stmts["whereStmt"] = " WHERE $column $type :$column";
        } else {
            $this->stmts["whereStmt"] .= " AND $column $type :$column";
        }
        
        $this->binding[$column] = $value;
        
        return $this;
    }
    
    public function orWhere($column, $value, $type = NewModel::EQUAL) {
        if (strpos($this->stmts['whereStmt'], "WHERE") === false) {
            throw new Exception("Please call the where() first before calling this function");
        } else {
            $this->stmts["whereStmt"] .= " OR $column $type :$column";
            
            $this->binding[$column] = $value;
        }
        
        return $this;
    }
    
    public function orderBy($column, $direction = "ASC") {
        if ($this->queryType !== "Select") {
            throw new Exception("orderBy() can be only called in a select query");
        }
        
        if ($this->stmts["orderStmt"] === "") {
            $this->stmts["orderStmt"] = " ORDER BY $column $direction";
        } else {
            throw new Exception("orderBy() can be only called once");
        }
        
        return $this;
    }
    
    public function limit($limit = 10) {
        if ($this->queryType !== "Select") {
            throw new Exception("limit() can be only called in a select query");
        }
        
        if ($this->stmts["limitStmt"] === "") {
            $this->stmts["limitStmt"] = " LIMIT $limit";
        } else {
            throw new Exception("limit() can be only called once");
        }
        
        return $this;
    }
    
    public function offset($offset = 0) {
        if ($this->queryType !== "Select") {
            throw new Exception("offset() can be only called in a select query");
        }
        
        if ($this->stmts["offsetStmt"] === "") {
            $this->stmts["offsetStmt"] = " OFFSET $offset";
        } else {
            throw new Exception("offset() can be only called once");
        }
        
        return $this;
    }
    
    public function execute() {
        $sql = "";
        foreach ($this->stmts as $stmt) {
            $sql .= $stmt;
        }
        $tempBinding = $this->binding;
        
        $this->initialiseStmt();
        
        return $this->db->query($sql, $tempBinding);
    }
    
    private function initialiseStmt() {
        foreach ($this->stmts as $key => $value) {
            $this->stmts[$key] = "";
        }
        $this->queryType = null;
        $this->binding = [];
    }
}