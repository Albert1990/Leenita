<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_id',
        'name',
        'mobile_number',
        'account_type',
        'facebook_token',
        'photo',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
//    protected $hidden = [
//        'password', 'remember_token',
//    ];

    public $token;

    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    public function interests()
    {
        return $this->belongsToMany('App\Tag','interests');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role','user_roles');
    }

    public function media()
    {
        return $this->belongsToMany('App\Medium','user_media');
    }
}
