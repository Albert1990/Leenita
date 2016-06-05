<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    protected $fillable=[
        'brand_id',
    ];

    public function translations()
    {
        return $this->hasMany('App\TermTranslation');
    }

    public function translation($language_id)
    {
        $translations=$this->hasMany('App\TermTranslation');
        return $translations->where('language_id',intval($language_id))->first();
    }
}
