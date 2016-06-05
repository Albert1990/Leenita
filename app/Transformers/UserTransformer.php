<?php

namespace App\Transformers;

use App\Http\Helpers;
use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract{
    protected $defaultIncludes=[
        'country',
    ];

    public function transform(User $user)
    {
        return [
            'id'=>$user->id,
            'name'=>$user->name,
            'mobile_number'=>$user->mobile_number,
            'email'=>$user->email,
            'points'=>$user->points,
            'is_verified'=>$user->is_verified,
            'location'=>$user->location,
            'photo'=>Helpers::getImageFullPath($user->photo),
            'notifications_count'=>$user->notifications_count,
            'rank'=>$user->rank,
            'imei'=>$user->imei,
            'token'=>$user->token,
        ];
    }

    public function includeCountry(User $user)
    {
        return $this->item($user->country,new CountryTransformer());
    }
}