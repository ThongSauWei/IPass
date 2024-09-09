<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of UnitConverterAdapter
 *
 * @author Acer
 */


require_once __DIR__ . '/ProductInterface.php';


class UnitConverterAdapter implements ProductInterface{
    //put your code here
    public function convert($kg) {
        return $kg * 1000; // Convert kg to grams
    }
}
