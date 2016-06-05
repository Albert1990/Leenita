<?php
namespace App\Containers;

class ProductContainer {
    public $product;
    public $product_translation;
    public $term_container;

    function __construct($product,$product_translation,$term_container)
    {
        $this->product=$product;
        $this->product_translation=$product_translation;
        $this->term_container=$term_container;
    }
}