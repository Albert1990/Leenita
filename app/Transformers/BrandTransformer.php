<?php
/**
 * Created by PhpStorm.
 * User: Albert
 * Date: 4/22/2016
 * Time: 1:00 AM
 */

namespace App\Transformers;


use App\Brand;
use App\BrandContact;
use App\Http\Helpers;
use League\Fractal\TransformerAbstract;

class BrandTransformer extends TransformerAbstract{
    protected $defaultIncludes = [
        'contacts',
        'tags',
        'branches',
    ];

    public function transform(Brand $brand)
    {
        return [
            'id'=>$brand->id,
            'name'=>$brand->name,
            'location'=>$brand->location_x.','.$brand->location_y,
            'logo'=>Helpers::getImageFullPath($brand->logo),
        ];
    }

    public function includeContacts(Brand $brand){
        return $this->collection($brand->contacts,new BrandContactTransformer());
    }

    public function includeTags(Brand $brand){
        return $this->collection($brand->tags, new TagTransformer());
    }

    public function includeBranches(Brand $brand){
        return $this->collection($brand->branches, new BranchTransformer());
    }
}