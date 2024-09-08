<?php

require_once __DIR__ . '/../models/Product.php'; // Updated path using __DIR__
require_once __DIR__ . '/../adapter/ProductInterface.php'; // Updated path using __DIR__
require_once __DIR__ . '/../adapter/UnitConverterAdapter.php';
require_once __DIR__ . '/../adapter/CurrencyConverterAdapter.php';

class ProductController {
    private $product;

    public function __construct() {
        $this->product = new Product([]);
    }

    // Add a product (Admin)
    public function addProduct($productData) {
        $this->product = new Product($productData);
        return $this->product->addProduct();
    }

    // Unit conversion (Kg to Gram)
    public function convertKgToGram($kg) {
        $converter = new UnitConverterAdapter();
        return $converter->convert($kg);
    }

    // Currency conversion (MYR to another currency)
    public function convertCurrency($myr, $exchangeRate) {
        $converter = new CurrencyConverterAdapter($exchangeRate);
        return $converter->convert($myr);
    }
}
