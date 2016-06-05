<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix'=>'api'],function(){


    Route::get('countries','\App\Http\Api\V1\Controllers\CountriesController@index');
    Route::get('offers','\App\Http\Api\V1\Controllers\OffersController@index');

    // products routes
    Route::get('products/{id}/{language_id}',
        '\App\Http\Api\V1\Controllers\ProductsController@show')
        ->where('id','[0-9]+')
        ->where('language_id','[0-9]+');
    Route::get('products/{product_id}/media',
        '\App\Http\Api\V1\Controllers\ProductsController@media')
        ->where('product_id','[0-9]+');



    // brands routes
    Route::get('brands',
        '\App\Http\Api\V1\Controllers\BrandsController@index');
    Route::get('brands/{id}',
        '\App\Http\Api\V1\Controllers\BrandsController@show')
        ->where('id','[0-9]+');
    Route::delete('brands/{id}',
        '\App\Http\Api\V1\Controllers\BrandsController@destroy')
        ->where('id','[0-9]+');
    Route::get('brands/{id}/products/{language_id}',
        '\App\Http\Api\V1\Controllers\BrandsController@products')
        ->where('id','[0-9]+')
        ->where('language_id','[0-9]+');
    Route::get('brands/{brand_id}/tags',
        '\App\Http\Api\V1\Controllers\BrandsController@tags')
        ->where('brand_id','[0-9]+');
    Route::get('brands/{brand_id}/branches',
        '\App\Http\Api\V1\Controllers\BrandsController@branches')
        ->where('brand_id','[0-9]+');
    Route::get('brands/{brand_id}/media',
        '\App\Http\Api\V1\Controllers\BrandsController@media')
        ->where('brand_id','[0-9]+');


    // users routes
    Route::post('auth/register','\App\Http\Api\V1\Controllers\UsersController@store');
    Route::post('auth/login','\App\Http\Api\V1\Controllers\UsersController@login');
    Route::get('users/{id}','\App\Http\Api\V1\Controllers\UsersController@show');
    Route::get('users/{id}/media','\App\Http\Api\V1\Controllers\UsersController@media');


    Route::group(['middleware'=>'jwt.auth'],function(){
        // products routes
        Route::post('products',
            '\App\Http\Api\V1\Controllers\ProductsController@store');
        Route::put('products/{id}',
            '\App\Http\Api\V1\Controllers\ProductsController@update')
            ->where('id','[0-9]+');
        Route::delete('products/{id}',
            '\App\Http\Api\V1\Controllers\ProductsController@destroy')
            ->where('id','[0-9]+');
        Route::post('products/{product_id}/media',
            '\App\Http\Api\V1\Controllers\ProductsController@addMedia')
            ->where('product_id','[0-9]+');
        Route::delete('products/{product_id}/media/{media_id}',
            '\App\Http\Api\V1\Controllers\ProductsController@deleteMedia')
            ->where('product_id','[0-9]+')
            ->where('media_id','[0-9]+');



        // brands routes
        Route::post('brands',
            '\App\Http\Api\V1\Controllers\BrandsController@store');
        Route::put('brands/{id}',
            '\App\Http\Api\V1\Controllers\BrandsController@update')
            ->where('id','[0-9]+');
        Route::delete('brands/{id}',
            '\App\Http\Api\V1\Controllers\BrandsController@destroy')
            ->where('id','[0-9]+');
        Route::post('brands/{brand_id}/tags',
            '\App\Http\Api\V1\Controllers\BrandsController@assignTag')
            ->where('brand_id','[0-9]+');
        Route::delete('brands/{brand_id}/tags/{tag_id}',
            '\App\Http\Api\V1\Controllers\BrandsController@deassignTag')
            ->where('brand_id','[0-9]+')
            ->where('tag_id','[0-9]+');
        Route::post('brands/{brand_id}/branches',
            '\App\Http\Api\V1\Controllers\BrandsController@addBranch')
            ->where('brand_id','[0-9]+');
        Route::delete('brands/{brand_id}/branches/{branch_id}',
            '\App\Http\Api\V1\Controllers\BrandsController@deleteBranch')
            ->where('brand_id','[0-9]+')
            ->where('branch_id','[0-9]+');
        Route::post('brands/{brand_id}/media',
            '\App\Http\Api\V1\Controllers\BrandsController@addMedia')
            ->where('brand_id','[0-9]+');
        Route::delete('brands/{brand_id}/media/{media_id}',
            '\App\Http\Api\V1\Controllers\BrandsController@deleteMedia')
            ->where('brand_id','[0-9]+')
            ->where('media_id','[0-9]+');



        // users routes
        Route::put('users',
            '\App\Http\Api\V1\Controllers\UsersController@update')
            ->where('id','[0-9]+');
        Route::post('users/media','\App\Http\Api\V1\Controllers\UsersController@addMedia');
        Route::delete('users/media/{media_id}','\App\Http\Api\V1\Controllers\UsersController@deleteMedia');


        // verifications routes
        Route::post('verifications','\App\Http\Api\V1\Controllers\VerificationsController@send');
        Route::put('verifications','\App\Http\Api\V1\Controllers\VerificationsController@verify');
    });






    // start brands routes

    // end brands routes

});
