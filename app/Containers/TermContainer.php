<?php
namespace App\Containers;

class TermContainer {
    public $term;
    public $term_translation;

    function __construct($term,$term_translation){
        $this->term=$term;
        $this->term_translation=$term_translation;
    }
}