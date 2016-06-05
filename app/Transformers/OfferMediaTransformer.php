<?php
/**
 * Created by PhpStorm.
 * User: Albert
 * Date: 4/22/2016
 * Time: 1:13 AM
 */

namespace App\Transformers;


use App\Http\Helpers;
use App\OfferMedia;
use League\Fractal\TransformerAbstract;

class OfferMediaTransformer extends TransformerAbstract{

    public function transform(OfferMedia $offerMedia)
    {
        return [
            'id'=>$offerMedia->id,
            'path'=>Helpers::getImageFullPath($offerMedia->path),
            'type'=>$offerMedia->type,
            'size'=>$offerMedia->size,
        ];
    }
}