<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPInterface.php to edit this template
 */

/**
 *
 * @author Acer
 */
interface CurrencyAdapterInterface {
    public function convert($amount, $fromCurrency, $toCurrency);
}
