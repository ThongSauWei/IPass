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
require_once __DIR__ . '/CurrencyAdapterInterface.php';

class CurrencyConverterAdapter implements CurrencyAdapterInterface {

    private $exchangeRates = [
        'MYR' => 1, // Base currency
        'USD' => 4.21,
        'SGD' => 3.26,
        'CNY' => 0.64,
        'EUR' => 4.80,
        'GBP' => 5.59,
    ];

//    public function convert($amount, $fromCurrency, $toCurrency) {
//        if (isset($this->exchangeRates[$fromCurrency]) && isset($this->exchangeRates[$toCurrency])) {
//            // Convert amount to MYR first, then to the target currency
//            $amountInMYR = $amount / $this->exchangeRates[$fromCurrency];
//            return $amountInMYR * $this->exchangeRates[$toCurrency];
//        }
//
//        return null; 
//    }

    public function convert($amount, $fromCurrency, $toCurrency) {
        if (isset($this->exchangeRates[$fromCurrency]) && isset($this->exchangeRates[$toCurrency])) {
            $amountInMYR = $amount / $this->exchangeRates[$fromCurrency];
            return $amountInMYR * $this->exchangeRates[$toCurrency];
        }
        return null; 
    }
}
