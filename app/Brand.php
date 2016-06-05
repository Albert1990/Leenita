<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable=[
        'user_id',
        'category_id',
        'name'=>'Samsung',
        'mobile_number',
        'phone',
        'email',
        'logo',
        'likes',
    ];

    public function products()
    {
        return $this->hasMany('App\Product');
    }

    public function packages()
    {
        return $this->belongsToMany('App\Packages','user_packages');
    }

    public function contacts()
    {
        return $this->hasMany('App\BrandContact');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag','brand_tags');
    }

    public function branches()
    {
        return $this->hasMany('App\Branch');
    }

    public function media()
    {
        return $this->belongsToMany('App\Medium','brand_media');
    }
}
