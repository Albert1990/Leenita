<?php

namespace App;

use App\Http\XobjectTypes;
use Illuminate\Database\Eloquent\Model;

class Xobject extends Model
{
    public function getObject()
    {
        $result_object=null;
        switch($this->type)
        {
            case XobjectTypes::PRODUCT:
                return $this->hasOne('App\Product');
                break;
            case XobjectTypes::DEAL:
                return $this->hasOne('App\Product');
                break;
        }
    }

}
