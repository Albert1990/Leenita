<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TermTranslation extends Model
{
    protected $fillable=[
        'term_id',
        'language_id',
        'title',
        'content',
    ];
    
    public function term()
    {
        return $this->belongsTo('App\Term');
    }
}
