<?php
/**
 * Created by PhpStorm.
 * User: Albert
 * Date: 4/22/2016
 * Time: 1:11 AM
 */

namespace App;


class OfferMedia {
    public $id;
    public $offer_id;
    public $path;
    public $type;
    public $size;

    function __construct($id, $offer_id, $type, $path, $size)
    {
        $this->id = $id;
        $this->offer_id = $offer_id;
        $this->type = $type;
        $this->path = $path;
        $this->size = $size;
    }


}