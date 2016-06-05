<?php
/**
 * Created by PhpStorm.
 * User: Albert
 * Date: 4/8/2016
 * Time: 11:08 AM
 */

namespace App\Http;

use App\Http\Enums\FileTypes;
use App\Http\Enums\MediaTypes;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Serializer\MyCustomArraySerializer;

class Helpers {
    private static $fractal=null;
    const URL_PREFIX='http://104.217.253.15:3005/';

    public static function getRandomInteger($min = 1, $max = 1000000)
    {
        return rand($min, $max);
    }

    public static function transformObject($object,$transformer)
    {
        if(Helpers::$fractal==null) {
            Helpers::$fractal = new Manager();
            Helpers::$fractal->setSerializer(new MyCustomArraySerializer());
        }
        $item = new Item($object,$transformer);
        $responseData = Helpers::$fractal->createData($item)->toArray();
        return $responseData;
    }

    public static function transformArray($arr,$transformer)
    {
        if(Helpers::$fractal==null) {
            Helpers::$fractal = new Manager();
            Helpers::$fractal->setSerializer(new MyCustomArraySerializer());
        }
        $collection = new Collection($arr,$transformer);
        $responseData = Helpers::$fractal->createData($collection)->toArray();
        return $responseData;
    }

    public static function checkMobileNumberFormat($mobile_number)
    {
        $mobile_number_result=null;
        $number_key=substr($mobile_number,0,1);
        if($number_key=='+')
            $mobile_number_result=$mobile_number;
        else{
            $number_key=substr($mobile_number,0,2);
            if($number_key=='00')
                $mobile_number_result='+'.substr($mobile_number,2,strlen($mobile_number));
        }
        return $mobile_number_result;

    }

    public static function getImageFullPath($path)
    {
        if($path==null || $path=='')
            $path='images/default.png';
        return Helpers::URL_PREFIX.$path;
    }

    public static function generateQrCode()
    {
        return rand(1, 10000000);
    }

    public static function formatValidationRules($rules)
    {
        $finalRules='';
        foreach($rules as $rule)
            $finalRules.=$rule.'|';
        $finalRules=substr($finalRules,0,strlen($finalRules)-1);
        return $finalRules;
    }

    public static function getFileType($extension)
    {
        $photoExtensions = ['jpg','png','bmp','gif','jpeg'];
        $videoExtensions = ['mp4,flv'];
        $mediaType = MediaTypes::UNKNOWN;
        if(in_array($extension,$photoExtensions))
            $mediaType = MediaTypes::IMAGE;
        if(in_array($extension,$videoExtensions))
            $mediaType = MediaTypes::VIDEO;
        return $mediaType;
    }

}