<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPInterface.php to edit this template
 */

/**
 *
 * @author Acer
 */
interface WeightAdapterInterface {
    public function convertToKg(float $weight): float;
    public function convertToG(float $weight): float;
}
