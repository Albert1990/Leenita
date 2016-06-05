<?php
/**
 * Created by PhpStorm.
 * User: Albert
 * Date: 6/2/16
 * Time: 7:58 PM
 */

namespace App\Transformers;


use App\Branch;
use League\Fractal\TransformerAbstract;

class BranchTransformer extends TransformerAbstract{

    public function transform(Branch $branch)
    {
        return [
            'name' => $branch->name,
            'location' => $branch->location_x.','.$branch->location_y,
            'phone' => $branch->phone,
            'fax' => $branch->fax,
            'email' => $branch->email,
            'website' =>$branch->website,
        ];
    }
}