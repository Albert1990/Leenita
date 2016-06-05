<?php
/**
 * Created by PhpStorm.
 * User: Albert
 * Date: 4/4/2016
 * Time: 8:55 AM
 */

namespace App\Http\Api\V1\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Enums\ErrorMessages;
use App\Http\Enums\HttpStatus;
use App\Http\Requests;
use App\Language;
use Illuminate\Http\Response as IlluminateResponse;


class ApiController extends Controller{
    private $status_code=IlluminateResponse::HTTP_OK;
    private $errorMessage='';
    protected $default_language;

    function __construct()
    {
        $this->default_language=Language::first();
    }

    public function getStatusCode()
    {
        return $this->status_code;
    }

    public function setStatusCode($statusCode)
    {
        $this->status_code=$statusCode;
        return $this;
    }

    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage=$errorMessage;
        return $this;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function respond($data=null,$statusCode=HttpStatus::OK,$headers=[])
    {
        return response()->json($data,$statusCode,$headers);
    }

    public function respondError($statusCode,$errorMessage,$moreInfo='',$headers=[]){
        $dataToSend=[
            'error'=>$errorMessage,
        ];
        if($moreInfo != '')
            $dataToSend['info'] = $moreInfo;
        return response()->json($dataToSend,$statusCode,$headers);
    }

    public function respondValidationFailed($moreInfo='')
    {
        return $this->respondError(HttpStatus::BAD_REQUEST,ErrorMessages::VALIDATION_ERROR,$moreInfo);
    }

    public function respondUnknownException(\Exception $ex)
    {
        return $this->respondError(HttpStatus::BAD_REQUEST,ErrorMessages::UNKNOWN_EXCEPTION,
            $ex->getMessage().' in '.$ex->getFile().' in Line :'.$ex->getLine());
    }

    public function respondModelNotFound($moreInfo=''){
        return $this->respondError(HttpStatus::BAD_REQUEST,ErrorMessages::MODEL_NOT_FOUND,$moreInfo);
    }

    public function respondNotImplementedException()
    {
        return $this->respondError(HttpStatus::BAD_REQUEST,ErrorMessages::UNKNOWN_EXCEPTION,'not implemented yet');
    }

    public function respondUnauthorized()
    {
        return $this->respondError(HttpStatus::UNAUTHORIZED,ErrorMessages::UNAUTHORIZED);
    }

}