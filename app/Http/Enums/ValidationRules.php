<?php
/**
 * Created by PhpStorm.
 * User: Albert
 * Date: 4/8/2016
 * Time: 11:09 AM
 */

namespace App\Http\Enums;


class ValidationRules {
    const PHONE='digits_between:10,25';
    const REQUIRED = 'required';
    const ID='required|numeric|min:1';
    const TYPE='numeric|min:1';
//    const BRAND_ID='required|numeric|min:1|exists:brands.id';
    const PRICE='numeric|min:0';
    const EMAIL='email';
    const WEBSITE='url';
    const NAME = 'string|min:2|max:100';
    const DESCRIPTION='string|min:0|max:4000';
    const VERIFICATION_CODE = 'digits:4';

    // product validation rules


    // brand validation rules

    // user validation rules

}