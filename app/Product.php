<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function xobject()
    {
        return $this->belongsTo('App\Xobject');
    }

    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }

    public function media()
    {
        return $this->belongsToMany('App\Medium','product_media');
    }

    public function translations()
    {
        return $this->hasMany('App\ProductTranslation');
    }

    public function translation($language_id)
    {
        $translations=$this->hasMany('App\ProductTranslation');
        return $translations->where('language_id',intval($language_id))->first();
    }

    public function term()
    {
        return $this->belongsTo('App\Term');
    }
}
