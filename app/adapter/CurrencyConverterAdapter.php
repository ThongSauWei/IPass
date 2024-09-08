<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of CurrencyConverterAdapter
 *
 * @author Acer
 */

require_once __DIR__ . '/ProductInterface.php';

class CurrencyConverterAdapter implements ProductInterface{
    private $exchangeRate; // exchange rate for MYR to target currency

    public function __construct($exchangeRate) {
        $this->exchangeRate = $exchangeRate;
    }

    public function convert($myr) {
        return $myr * $this->exchangeRate;
    }
}
