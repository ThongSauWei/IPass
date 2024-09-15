<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of ProductLogger
 *
 * @author Acer
 */
class ProductLogger {
    private $logFile;

    public function __construct($logFile = __DIR__ . '/../logs/product_log.txt') {
        $this->logFile = $logFile;

        // Create the log directory if it doesn't exist
        $logDir = dirname($this->logFile);
        if (!is_dir($logDir)) {
            if (!mkdir($logDir, 0755, true)) {
                throw new Exception("Unable to create log directory: $logDir");
            }
        }

        // Create the log file if it doesn't exist
        if (!file_exists($this->logFile)) {
            if (!touch($this->logFile)) {
                throw new Exception("Unable to create log file: $this->logFile");
            }
        }
    }

    public function log($message) {
        $time = date('Y-m-d H:i:s');
        if (file_put_contents($this->logFile, "[$time] $message\n", FILE_APPEND) === false) {
            throw new Exception("Unable to write to log file: $this->logFile");
        }
    }
}
