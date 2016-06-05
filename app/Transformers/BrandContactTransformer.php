<?php
/**
 * Created by PhpStorm.
 * User: Albert
 * Date: 5/30/16
 * Time: 7:40 PM
 */

namespace App\Transformers;


use App\BrandContact;
use League\Fractal\TransformerAbstract;

class BrandContactTransformer extends TransformerAbstract{

    public function transform(BrandContact $brandContact){
        return [
            'id' => $brandContact->id,
            'type' => $brandContact->type,
            'value' => $brandContact->value,
        ];
    }
}