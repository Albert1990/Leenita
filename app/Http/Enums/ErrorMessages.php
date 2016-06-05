<?php
/**
 * Created by PhpStorm.
 * User: Albert
 * Date: 5/29/16
 * Time: 7:14 PM
 */

namespace App\Http\Enums;


class ErrorMessages {
    const VALIDATION_ERROR='VALIDATION_ERROR';
    const USER_EXIST_BEFORE='USER_EXIST_BEFORE';
    const USER_NOT_EXIST='USER_NOT_EXIST';
    const UNAUTHENTICATED='UNAUTHENTICATED';
    const UNAUTHORIZED='UNAUTHORIZED';
    const MODEL_NOT_FOUND='MODEL_NOT_FOUND';


    const UNKNOWN_EXCEPTION='UNKNOWN_EXCEPTION';
}