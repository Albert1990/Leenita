<?php
/**
 * Created by PhpStorm.
 * User: Albert
 * Date: 6/1/16
 * Time: 8:18 PM
 */

namespace App\Transformers;


use App\Http\Helpers;
use App\Medium;
use App\UserMedia;
use League\Fractal\TransformerAbstract;

class MediumTransformer extends TransformerAbstract{

    public function transform(Medium $media)
    {
        return [
            'id' => $media->id,
            'creator_id' => $media->creator_id,
            'path' => Helpers::getImageFullPath($media->path),
            'size' => $media->size,
            'type' => $media->type,
        ];
    }
}