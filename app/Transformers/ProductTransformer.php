<?php
/**
 * Created by PhpStorm.
 * User: Albert
 * Date: 4/17/2016
 * Time: 9:05 PM
 */

namespace App\Transformers;


use App\Product;
use League\Fractal\TransformerAbstract;
use App\Containers\ProductContainer;

class ProductTransformer extends TransformerAbstract{
    protected $defaultIncludes=[
        'brand',
        'media',
        'term',
    ];

    public function transform(ProductContainer $productContainer)
    {
        $product=$productContainer->product;
        $productTranslation=$productContainer->product_translation;
        return [
            'id'=>$product->id,
            'name'=>$productTranslation->name,
            'description'=>$productTranslation->description,
            'price'=>$product->price,
        ];
    }

    public function includeBrand(ProductContainer $productContainer)
    {
        return $this->item($productContainer->product->brand,new BrandTransformer());
    }

    public function includeMedia(ProductContainer $productContainer)
    {
        return $this->collection($productContainer->product->media, new MediumTransformer());
    }

    public function includeTerm(ProductContainer $productContainer)
    {
        return $this->item($productContainer->term_container,new TermTransformer());
    }
}