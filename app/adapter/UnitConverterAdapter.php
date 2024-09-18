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
require_once __DIR__ . '/WeightAdapterInterface.php';

class UnitConverterAdapter implements WeightAdapterInterface {

    public function convertToKg(float $weight): float {
        return $weight; 
    }

    public function convertToG(float $weight): float {
        return $weight * 1000; // Convert kg to grams
    }

}
