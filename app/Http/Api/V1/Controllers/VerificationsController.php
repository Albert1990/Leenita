<?php

namespace App\Http\Api\V1\Controllers;

use App\Http\Enums\HttpStatus;
use App\Http\Enums\ValidationRules;
use App\Http\Enums\VerificationSendingWays;
use App\Http\Helpers;
use App\Verification;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VerificationsController extends ApiController
{

    /**
     * Send verification to user
     *
     * @api {post} /verifications Send Verification Code
     * @apiName SendVerificationCode
     * @apiGroup Verifications
     *
     *
     * @apiSuccess Status 204:NO_CONTENT:
     *
     * @apiError {String} UNKNOWN_EXCEPTION
     *
     * @apiErrorExample {json} Error-Response:
     *   {
     *   "error": "UNKNOWN_EXCEPTION",
     *   "info": "exception info"
     *   }
     *
     */
    public function send()
    {
        try {
            $user = Auth::user();
            $verification = new Verification();
            $verification->user_id = $user->id;
            $verification->code = Helpers::getRandomInteger(1000, 9999);
            $verification->sent_over = VerificationSendingWays::GCM_SERVER;
            $verification->has_destroyed = false;
            $verification->save();

            return $this->respond(null,HttpStatus::NO_CONTENT);
        }catch (\Exception $ex){
            return $this->respondUnknownException($ex);
        }
    }

    /**
     * Verify user code
     *
     * @api {put} /verifications Verify User Code
     * @apiName VerifyUserCode
     * @apiGroup Verifications
     *
     *
     * @apiSuccess Status 204:NO_CONTENT:
     *
     * @apiError {String} VALIDATION_ERROR wrong verification code
     * @apiError {String} UNKNOWN_EXCEPTION
     *
     * @apiErrorExample {json} Error-Response:
     *   {
     *   "error": "UNKNOWN_EXCEPTION",
     *   "info": "exception info"
     *   }
     *
     */
    public function verify(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'verification_code' => Helpers::formatValidationRules([ValidationRules::REQUIRED, ValidationRules::VERIFICATION_CODE]),
            ]);
            if ($validator->fails())
                return $this->respondValidationFailed($validator->errors());

            $user = Auth::user();
            $user->is_verified = false;
            $verification = Verification::where('code', $request['verification_code'])
                ->where('user_id', $user->id)
                ->where('has_destroyed', false)
                ->first();
            if ($verification == null) {
                $user->save();
                return $this->respondModelNotFound('verification code wrong');
            }

            $verification->acceptance_date = Carbon::now();
            $verification->has_destroyed = true;
            $verification->save();
            $user->is_verified = true;
            $user->save();

            return $this->respond(null, HttpStatus::NO_CONTENT);
        }catch (\Exception $ex){
            return $this->respondUnknownException($ex);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
