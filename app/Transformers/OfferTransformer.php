<?php
/**
 * Created by PhpStorm.
 * User: Albert
 * Date: 4/17/2016
 * Time: 9:16 PM
 */

namespace App\Transformers;


use App\Offer;
use League\Fractal\TransformerAbstract;

class OfferTransformer extends TransformerAbstract{
    protected $defaultIncludes=[
        'brand',
        'product',
        'media',
    ];

    public function transform(Offer $offer)
    {
        return [
            'id'=>$offer->id,
            'type'=>$offer->type,
            'discount'=>$offer->discount,
            'description'=>$offer->description,
        ];
    }

    public function includeBrand(Offer $offer)
    {
        return $this->item($offer->brand,new BrandTransformer());
    }

    public function includeProduct(Offer $offer)
    {
        return $this->item($offer->product,new ProductTransformer());
    }

    public function includeMedia(Offer $offer)
    {
        return $this->collection($offer->media,new OfferMediaTransformer());
    }
}