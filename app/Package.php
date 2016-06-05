<?php

namespace App;

use App\Http\PackageTypes;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    public $timestamps=false;

    public function scopeBasic($query)
    {
        return $query->where('name','basic')->first();
    }

    public function users()
    {
        return $this->belongsToMany('App\User','user_packages');
    }
}
