<?php
/**
 * Created by PhpStorm.
 * User: Albert
 * Date: 4/8/2016
 * Time: 3:30 PM
 */

namespace App\Transformers;


use App\Country;
use League\Fractal\TransformerAbstract;

class CountryTransformer extends TransformerAbstract{

    public function transform(Country $country)
    {
        return [
            'id'=>$country->id,
            'code'=>$country->iso2,
            'name'=>$country->short_name,
        ];
    }
}