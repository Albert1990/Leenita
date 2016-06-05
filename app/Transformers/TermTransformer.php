<?php
/**
 * Created by PhpStorm.
 * User: Albert
 * Date: 5/29/16
 * Time: 12:26 AM
 */

namespace App\Transformers;


use App\Term;
use App\TermTranslation;
use League\Fractal\TransformerAbstract;
use App\Containers\TermContainer;

class TermTransformer extends TransformerAbstract{

    public function transform(TermContainer $termContainer){
        $term=$termContainer->term;
        $termTranslation=$termContainer->term_translation;

        return [
            'id'=>$term->id,
            'brand_id'=>$term->brand_id,
            'title'=>$termTranslation->title,
            'content'=>$termTranslation->content,
        ];
    }

}