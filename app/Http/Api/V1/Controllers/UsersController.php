<?php

namespace App\Http\Api\V1\Controllers;

use App\Http\Enums\ErrorMessages;
use App\Http\Enums\MediaTypes;
use App\Http\Helpers;
use App\Http\Enums\HttpStatus;
use App\Http\Enums\ValidationRules;
use App\Medium;
use App\Transformers\ContactTransformer;
use App\Transformers\MediumTransformer;
use App\Transformers\UserMediaTransformer;
use App\Transformers\UserTransformer;
use App\User;
use App\UserMedia;
use App\Verification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsersController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        try {
//            $users = User::all();
//            $transformedUsers = Helpers::transformArray($users, new UserTransformer());
//            return $this->respond($transformedUsers);
//        }catch (\Exception $ex)
//        {
//            return $this->respondUnknownException($ex->getMessage());
//        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @api {post} /auth/register Register User
     * @apiName RegisterUser
     * @apiGroup Users
     *
     * @apiParam {Number} country_id Country unique id
     * @apiParam {String} name product name
     * @apiParam {String} mobile_number mobile number
     * @apiParam {String} imei device imei
     * @apiParam {String} facebook_token facebook token he logged in through facebook (optional)
     *
     *
     * @apiSuccessExample {json} Success-Response:
     * {"id":3,"name":"Bobo Shatta","mobile_number":"+96176309030","email":"","points":0,"is_verified":0,"location":null,"photo":"http:\/\/104.217.253.15:3005\/images\/default.png","notifications_count":0,"rank":0,"imei":"","token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMsImlzcyI6Imh0dHA6XC9cL2xvY2FsaG9zdDozMDAwXC9hcGlcL2F1dGhcL3JlZ2lzdGVyIiwiaWF0IjoxNDY0NjQ0NTgzLCJleHAiOjE0NjQ2NDgxODMsIm5iZiI6MTQ2NDY0NDU4MywianRpIjoiNmI2MWE5ZTQ1MGYwMDhmMzg2YjMzZjUzM2RjYzJkYmIifQ.QtcdiZyC0iHvGXxcqc_J8VftklzxdxQQdVyuKogWOB4","country":{"id":1,"code":"SY","name":"Syria"}}
     *
     * @apiError {String} VALIDATION_ERROR validation failed
     * @apiError {String} USER_EXIST_BEFORE, HttpStatus: Conflict (409)
     * @apiError {String} UNKNOWN_EXCEPTION
     *
     * @apiErrorExample {json} Error-Response:
     *   {
     *   "error": "UNKNOWN_EXCEPTION",
     *   "info": "exception info"
     *   }
     *
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'country_id' => ValidationRules::ID,
                'name' => Helpers::formatValidationRules([ValidationRules::REQUIRED, ValidationRules::NAME]),
                'mobile_number' => Helpers::formatValidationRules([ValidationRules::REQUIRED, ValidationRules::PHONE]),
                'imei' => ValidationRules::REQUIRED,
            ]);
            if ($validator->fails())
                return $this->respondValidationFailed($validator->errors());

            //check mobile number format
            $formatted_mobile_number = Helpers::checkMobileNumberFormat($request['mobile_number']);
            if ($formatted_mobile_number == null)
                return $this->respondValidationFailed('mobile format not accepted');

            //check if user exists before

            $user = User::where('mobile_number', $formatted_mobile_number)->first();
            if ($user != null)
                return $this->respondError(HttpStatus::CONFLICT, ErrorMessages::USER_EXIST_BEFORE);

            // we have to register that user...

            $userAttributes = [
                'country_id' => $request['country_id'],
                'name' => $request['name'],
                'mobile_number' => $formatted_mobile_number,
                'facebook_token' => $request->input['facebook_token'] != null ? $request['facebook_token'] : '',
            ];
            try {
                $user = User::create($userAttributes);
                $user = User::findOrFail($user->id);
            } catch (\Exception $ex) {
                return $this->respondUnknownException($ex);
            }
            try {
                $token = JWTAuth::fromUser($user);
                $user->token = $token;
            } catch (\Exception $ex) {
                return $this->respondUnknownException($ex);
            }

//            if($userAttributes['account_type']==AccountTypes::BUSINESS_PARTNER)
//            {
//                // add basic profile package
//                try {
//                    $basicProfilePackage = Package::Basic();
//                    $basicProfilePackage->users()->attach($user);
//                }catch(\Exception $ex){
//                    return $this->respondUnknownException('cant save basic profile:'.$ex->getMessage());
//                }
//            }
            $transformedUser = Helpers::transformObject($user, new UserTransformer());
            return $this->respond($transformedUser);
        }catch (\Exception $ex){
            return $this->respondUnknownException($ex);
        }
    }

    /**
     * Display the specified resource.
     *
     * @api {get} /users/{id} Request User Info
     * @apiName ShowUser
     * @apiGroup Users
     *
     *
     * @apiSuccessExample {json} Success-Response:
     * {"id":1,"name":"Samer Shatta","mobile_number":"+96176309032","email":"samer.shatta@gmail.com","location":null,"photo":"http:\/\/104.217.253.15:3005\/images\/users\/samer.jpg"}
     *
     *
     * @apiError {String} VALIDATION_ERROR validation failed
     * @apiError {String} UNKNOWN_EXCEPTION
     *
     * @apiErrorExample {json} Error-Response:
     *   {
     *   "error": "UNKNOWN_EXCEPTION",
     *   "info": "exception info"
     *   }
     *
     */
    public function show($id)
    {
        try
        {
            $user = User::find($id);
            if($user == null)
                return $this->respondModelNotFound();
            $transformedContact = Helpers::transformObject($user,new ContactTransformer());
            return $this->respond($transformedContact);
        }catch (\Exception $ex){
            return $this->respondUnknownException($ex);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @api {put} /users Update User
     * @apiName UpdateUser
     * @apiGroup Users
     *
     * @apiParam {Number} country_id Country unique id
     * @apiParam {String} name product name
     *
     *
     * @apiSuccessExample {json} Success-Response:
     * {"id":1,"name":"Beso Shatta","mobile_number":"+96176309032","email":"samer.shatta@gmail.com","points":0,"is_verified":0,"location":null,"photo":"http:\/\/104.217.253.15:3005\/images\/users\/samer.jpg","notifications_count":0,"rank":0,"imei":"","token":null,"country":{"id":1,"code":"SY","name":"Syria"}}
     *
     * @apiError {String} VALIDATION_ERROR validation failed
     * @apiError {String} MODEL_NOT_FOUND user model not found
     * @apiError {String} UNKNOWN_EXCEPTION
     *
     * @apiErrorExample {json} Error-Response:
     *   {
     *   "error": "UNKNOWN_EXCEPTION",
     *   "info": "exception info"
     *   }
     *
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_id' => ValidationRules::ID,
            'name' => Helpers::formatValidationRules([ValidationRules::REQUIRED, ValidationRules::NAME]),
        ]);
        if ($validator->fails())
            return $this->respondValidationFailed($validator->errors());

        try {
            $user = Auth::user();
            if ($user == null)
                return $this->respondModelNotFound();

            $user->country_id = $request['country_id'];
            $user->name = $request['name'];
            $user->save();

            $transformedUser = Helpers::transformObject($user, new UserTransformer());
            return $this->respond($transformedUser);
        }catch (\Exception $ex){
            return $this->respondUnknownException($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Login User
     *
     * @api {post} /auth/login Login
     * @apiName Login
     * @apiGroup Users
     *
     * @apiParam {String} mobile_number mobile number
     * @apiParam {String} imei device imei
     *
     *
     * @apiSuccessExample {json} Success-Response:
     * {"id":1,"name":"Samer Shatta","mobile_number":"+96176309032","email":"samer.shatta@gmail.com","points":0,"is_verified":0,"location":null,"photo":"http:\/\/104.217.253.15:3005\/images\/users\/samer.jpg","notifications_count":0,"rank":0,"imei":"","token":null,"country":{"id":1,"code":"SY","name":"Syria"}}
     *
     * @apiError {String} VALIDATION_ERROR validation failed
     * @apiError {String} MODEL_NOT_FOUND
     * @apiError {String} UNKNOWN_EXCEPTION
     *
     * @apiErrorExample {json} Error-Response:
     *   {
     *   "error": "UNKNOWN_EXCEPTION",
     *   "info": "exception info"
     *   }
     *
     */
    public function login(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'mobile_number'=>ValidationRules::PHONE,
            'imei'=>ValidationRules::REQUIRED,
        ]);
        if($validator->fails())
            return $this->respondValidationFailed($validator->errors());
        try {
            $formatted_mobile_number = Helpers::checkMobileNumberFormat($request['mobile_number']);
            if ($formatted_mobile_number == null)
                return $this->respondValidationFailed('mobile format not accepted');
        }catch(\Exception $ex){
            return $this->respondUnknownException($ex);
        }

        try {
            $user = User::where('mobile_number', $formatted_mobile_number)->firstOrFail();
            try {
                $token = JWTAuth::fromUser($user);
                $user->token = $token;
            } catch (\Exception $ex) {
                return $this->respondUnknownException($ex);
            }
            $transformedUser=Helpers::transformObject($user,new UserTransformer());
            return $this->respond($transformedUser);
        }catch (ModelNotFoundException $ex){
            return $this->respondModelNotFound();
        }
    }

    /**
     * List of user media
     *
     * @api {get} /users/{user_id}/media Request User Media
     * @apiName UserMedia
     * @apiGroup Users
     *
     *
     * @apiSuccessExample {json} Success-Response:
     * [{"id":1,"user_id":1,"path":"http:\/\/104.217.253.15:3005\/media\/users\/1\/","size":9169,"type":1},{"id":2,"user_id":1,"path":"http:\/\/104.217.253.15:3005\/media\/users\/1\/","size":9169,"type":1}]
     *
     * @apiError {String} MODEL_NOT_FOUND user model not found
     * @apiError {String} UNKNOWN_EXCEPTION
     *
     * @apiErrorExample {json} Error-Response:
     *   {
     *   "error": "UNKNOWN_EXCEPTION",
     *   "info": "exception info"
     *   }
     *
     */
    public function media($user_id)
    {
        try {
            $user = User::find($user_id);
            if($user == null)
                return $this->respondModelNotFound();
            $media = $user->media;
            $transformedMedia = Helpers::transformArray($media, new MediumTransformer());
            return $this->respond($transformedMedia);
        }catch (\Exception $ex){
            return $this->respondUnknownException($ex);
        }
    }

    /**
     * Add user media
     *
     * @api {post} /users/media store User Media
     * @apiName AddUserMedia
     * @apiGroup Users
     *
     *
     * @apiSuccessExample {json} Success-Response:
     * {"id":1,"user_id":"1","path":"http:\/\/104.217.253.15:3005\/media\/products\/1\/","size":9169,"type":1}
     *
     * @apiError {String} VALIDATION_ERROR validation error
     * @apiError {String} UNKNOWN_EXCEPTION
     *
     * @apiErrorExample {json} Error-Response:
     *   {
     *   "error": "UNKNOWN_EXCEPTION",
     *   "info": "exception info"
     *   }
     *
     */
    public function addMedia(Request $request)
    {
        try
        {
            if(!$request->hasFile('file'))
                return $this->respondValidationFailed('file not included');
            if(!$request->file('file')->isValid())
                return $this->respondValidationFailed('file not uploaded successfully');


            $user = Auth::user();
            $file = $request->file('file');
            $fileExtension = $file->getClientOriginalExtension();
            $mediaType = Helpers::getFileType($fileExtension);
            if($mediaType == MediaTypes::UNKNOWN)
                return $this->respondValidationFailed('file is not a photo nor a video');
            $filePath = 'media/users/'.$user->id.'/';
            $file->move($filePath);

            $media = new Medium();
            $media->creator_id = $user->id;
            $media->path = $filePath;
            $media->size = $file->getClientSize();
            $media->type = $mediaType;
            $media->save();

            $user->media->attach($media);

            $transformedMedia = Helpers::transformObject($media, new MediumTransformer());
            return $this->respond($transformedMedia);
        }catch (\Exception $ex){
            return $this->respondUnknownException($ex);
        }
    }

    /**
     * delete user media
     *
     * @api {delete} /users/media/{media_id} Delete User Media
     * @apiName DeleteUserMedia
     * @apiGroup Users
     *
     *
     * @apiSuccess Status NO_CONTENT: 204
     *
     * @apiError {String} MODEL_NOT_FOUND
     * @apiError {String} UNKNOWN_EXCEPTION
     *
     * @apiErrorExample {json} Error-Response:
     *   {
     *   "error": "UNKNOWN_EXCEPTION",
     *   "info": "exception info"
     *   }
     *
     */
    public function deleteMedia($media_id)
    {
        try
        {
            $user = Auth::user();
            $media = Medium::find($media_id);
            if($media == null)
                return $this->respondModelNotFound('media model not found');

            if($media->creator_id != $user->id)
                return $this->respondUnauthorized();

            $media->delete();
            return $this->respond(null,HttpStatus::NO_CONTENT);
        }catch (\Exception $ex){
            return $this->respondUnknownException($ex);
        }
    }

}
