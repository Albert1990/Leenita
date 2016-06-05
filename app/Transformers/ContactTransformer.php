<?php
/**
 * Created by PhpStorm.
 * User: Albert
 * Date: 4/17/2016
 * Time: 9:13 PM
 */

namespace App\Transformers;


use App\Brand;
use App\Http\Helpers;
use App\User;
use League\Fractal\TransformerAbstract;

class ContactTransformer extends TransformerAbstract{

    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'mobile_number' => $user->mobile_number,
            'email' => $user->email,
            'location' => $user->location,
            'photo' => Helpers::getImageFullPath($user->photo),
        ];
    }
}