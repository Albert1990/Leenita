<?php

namespace App\Http\Api\V1\Controllers;

use App\Brand;
use App\Http\Enums\MediaTypes;
use App\Http\Helpers;
use App\Http\Enums\HttpStatus;
use App\Http\Enums\ValidationRules;
use App\Http\Enums\XobjectTypes;
use App\Language;
use App\Medium;
use App\Product;
use App\ProductMedia;
use App\ProductTranslation;
use App\Term;
use App\Transformers\MediumTransformer;
use App\Transformers\ProductMediaTransformer;
use App\Transformers\ProductTransformer;
use App\User;
use App\Xobject;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Containers\ProductContainer;
use App\Containers\TermContainer;

class ProductsController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @api {post} /products Store Product
     * @apiName StoreProduct
     * @apiGroup Products
     *
     * @apiParam {Number} brand_id Brand unique id
     * @apiParam {Number} term_id Term unique id
     * @apiParam {Number} language_id language unique id
     * @apiParam {Number} price product price (can be float)
     * @apiParam {String} name product name
     * @apiParam {String} description product description
     *
     *
     * @apiSuccessExample {json} Success-Response:
     * {"id":1,"name":"Toshiba Tv","description":"It's great tv form Toshiba","price":"100","brand":{"id":1,"name":"Samsung","location":"0,0","logo":"http:\/\/104.217.253.15:3005\/images\/brands\/Samsung_Logo.png","contacts":[]},"media":[],"term":{"id":1,"brand_id":1,"title":"\u0627\u0644\u0634\u0631\u0648\u0637","content":"\u0627\u062d\u0630\u0631 \u0645\u0646 \u0647\u0630\u0647 \u0627\u0644\u0634\u0631\u0648\u0637"}}
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
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'brand_id'=>ValidationRules::ID,
            'term_id'=>ValidationRules::ID,
            'language_id'=>ValidationRules::ID,
            'price'=> Helpers::formatValidationRules([ValidationRules::REQUIRED,ValidationRules::PRICE]),
            'name'=> Helpers::formatValidationRules([ValidationRules::REQUIRED, ValidationRules::NAME]),
            'description'=> Helpers::formatValidationRules([ValidationRules::REQUIRED, ValidationRules::DESCRIPTION]),
        ]);
        if($validator->fails())
            return $this->respondValidationFailed($validator->errors());


        try {
            $user = Auth::user();
            $selected_brand = Brand::findOrFail($request['brand_id']);
            $selected_term = Term::findOrFail($request['term_id']);
            $selected_language = Language::findOrFail($request['language_id']);

            try {
                $xobject = new Xobject();
                $xobject->type = XobjectTypes::PRODUCT;
                $xobject->qr_code = Helpers::generateQrCode();
                $xobject->save();
            } catch (\Exception $ex) {
                return $this->respondUnknownException($ex);
            }

            try {
                $product = new Product();
                $product->xobject_id = $xobject->id;
                $product->creator_id = $user->id;
                $product->term_id = $request['term_id'];
                $product->brand_id = $request['brand_id'];
                $product->price = $request['price'];
                $product_photo = $request->input('photo', false);
                if ($product_photo !== false)
                    $product->photo = $product_photo;
                $product->save();

                $product_translation = new ProductTranslation();
                $product_translation->language_id = $request['language_id'];
                $product_translation->name = $request['name'];
                $product_translation->description = $request['description'];
                $product->translations()->save($product_translation);

                $selected_term_translation = $selected_term->translation($request['language_id']);//->where('language_id',intval($request['language_id']))->first();
                $product_container = new ProductContainer($product,
                    $product_translation,
                    new TermContainer($selected_term, $selected_term_translation));
                $transformed_product = Helpers::transformObject($product_container, new ProductTransformer());
                return $this->respond($transformed_product,HttpStatus::CREATED);
            } catch (\Exception $ex) {
                return $this->respondUnknownException($ex);
            }
        }catch (\Exception $ex){
            return $this->respondModelNotFound($ex);
        }
    }

    /**
     * Display the specified resource.
     *
     * @api {get} /products/{id}/{language_id?} Request Product info
     * @apiName GetProduct
     * @apiGroup Products
     *
     * @apiSuccessExample {json} Success-Response:
     * {"id":1,"name":"Toshiba Tv","description":"It's great tv form Toshiba","price":100,"brand":{"id":1,"name":"Samsung","location":"0,0","logo":"http:\/\/104.217.253.15:3005\/images\/brands\/Samsung_Logo.png","contacts":[]},"media":[],"term":{"id":1,"brand_id":1,"title":"\u0627\u0644\u0634\u0631\u0648\u0637","content":"\u0627\u062d\u0630\u0631 \u0645\u0646 \u0647\u0630\u0647 \u0627\u0644\u0634\u0631\u0648\u0637"}}
     *
     *
     * @apiError {String} MODEL_NOT_FOUND product with corresponding id not exists
     * @apiError {String} UNKNOWN_EXCEPTION
     *
     * @apiErrorExample {json} Error-Response:
     *   {
     *   "error": "VALIDATION_ERROR",
     *   "info": "product with corresponding id not exists"
     *   }
     */
    public function show($id,$language_id = 1)
    {
        try {
            $product = Product::find($id);
            if ($product == null)
                return $this->respondModelNotFound('product with corresponding id not exists');

            $productTranslation = $product->translation($language_id);
            if ($productTranslation == null) {
                $productTranslation = $product->translations->first();
                if ($productTranslation == null)
                    return $this->respondModelNotFound('no translation in this language for this product');
            }

            $termTranslation = $product->term->translation($language_id);
            if ($termTranslation == null) {
                $termTranslation = $product->term->translations->first();
                if ($termTranslation = null)
                    return $this->respondModelNotFound('no translation in this language for term & condition');
            }

            $productContainer = new ProductContainer($product,
                $productTranslation,
                new TermContainer($product->term, $termTranslation));
            $transformedProduct = Helpers::transformObject($productContainer, new ProductTransformer());
            return $this->respond($transformedProduct, HttpStatus::OK, ['scope' => 'admin,author']);
        }catch (\Exception $ex){
            return $this->respondUnknownException($ex);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @api {PUT} /products/{id} Update Product
     * @apiName UpdateProduct
     * @apiGroup Products
     *
     * @apiParam {Number} brand_id Brand unique id
     * @apiParam {Number} term_id Term unique id
     * @apiParam {Number} language_id language unique id
     * @apiParam {Number} price product price (can be float)
     * @apiParam {String} name product name
     * @apiParam {String} description product description
     *
     *
     * @apiSuccessExample {json} Success-Response:
     * {"id":1,"name":"Toshiba MssV","description":"It's great tv form Toshiba","price":"100","brand":{"id":1,"name":"Samsung","location":"0,0","logo":"http:\/\/104.217.253.15:3005\/images\/brands\/Samsung_Logo.png","contacts":[]},"media":[],"term":{"id":1,"brand_id":1,"title":"\u0627\u0644\u0634\u0631\u0648\u0637","content":"\u0627\u062d\u0630\u0631 \u0645\u0646 \u0647\u0630\u0647 \u0627\u0644\u0634\u0631\u0648\u0637"}}
     *
     *
     * @apiError {String} VALIDATION_ERROR validation failed
     * @apiError {String} UNAUTHORIZED
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
    public function update(Request $request, $id)
    {
        $validator=Validator::make($request->all(),[
            'brand_id'=>ValidationRules::ID,
            'term_id'=>ValidationRules::ID,
            'language_id'=>ValidationRules::ID,
            'price'=> Helpers::formatValidationRules([ValidationRules::REQUIRED,ValidationRules::PRICE]),
            'name'=> Helpers::formatValidationRules([ValidationRules::REQUIRED, ValidationRules::NAME]),
            'description'=> Helpers::formatValidationRules([ValidationRules::REQUIRED, ValidationRules::DESCRIPTION]),
        ]);
        if($validator->fails())
            return $this->respondValidationFailed($validator->errors());

        try {
            $user = Auth::user();
            $selected_brand = Brand::findOrFail($request['brand_id']);
            $selected_term = Term::findOrFail($request['term_id']);
            $selected_language = Language::findOrFail($request['language_id']);

            try {
                $product = Product::find($id);
                if ($product == null)
                    return $this->respondValidationFailed('product model not exists');

                if($product->creator_id != $user->id)
                    return $this->respondUnauthorized();

                $product->term_id = $request['term_id'];
                $product->brand_id = $request['brand_id'];
                $product->price = $request['price'];
                $product_photo = $request->input('photo', false);
                if ($product_photo !== false)
                    $product->photo = $product_photo;
                $product->save();


                $product_translation = $product->translation($request['language_id']);
                $product_translation->language_id = $request['language_id'];
                $product_translation->name = $request['name'];
                $product_translation->description = $request['description'];
                $product_translation->save();

                $selected_term_translation = $selected_term->translation($request['language_id']);//->where('language_id',intval($request['language_id']))->first();
                $product_container = new ProductContainer($product,
                    $product_translation,
                    new TermContainer($selected_term, $selected_term_translation));
                $transformed_product = Helpers::transformObject($product_container, new ProductTransformer());
                return $this->respond($transformed_product);
            } catch (\Exception $ex) {
                return $this->respondUnknownException($ex);
            }
        }catch (ModelNotFoundException $ex)
        {
            return $this->respondModelNotFound($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @api {delete} /products/{id} Delete Product
     * @apiName DeleteProduct
     * @apiGroup Products
     *
     *
     * @apiSuccess {Number} Status 204:NO_CONTENT
     *
     * @apiError {String} MODEL_NOT_FOUND
     * @apiError {String} UNAUTHORIZED
     * @apiError {String} UNKNOWN_EXCEPTION
     *
     * @apiErrorExample {json} Error-Response:
     *   {
     *   "error": "UNKNOWN_EXCEPTION",
     *   "info": "exception info"
     *   }
     *
     */
    public function destroy($id)
    {
        try
        {
            $user = Auth::user();

            $product = Product::find($id);
            if($product == null)
                return $this->respondModelNotFound();

            if($product->creator_id != $user->id)
                return $this->respondUnauthorized();

            $product->delete();
            return $this->respond(null,HttpStatus::NO_CONTENT);
        }catch (\Exception $ex){
            return $this->respondUnknownException($ex);
        }
    }

    /**
     * List of product media
     *
     * @api {get} /products/{product_id}/media Request Product Media
     * @apiName ProductMedia
     * @apiGroup Products
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
    public function media($product_id)
    {
        try {
            $product = Product::find($product_id);
            if($product == null)
                return $this->respondModelNotFound();
            $media = $product->media;
            $transformedMedia = Helpers::transformArray($media, new MediumTransformer());
            return $this->respond($transformedMedia);
        }catch (\Exception $ex){
            return $this->respondUnknownException($ex);
        }
    }

    /**
     * Add product media
     *
     * @api {post} /products/{product_id}/media Store Product Media
     * @apiName AddProductMedia
     * @apiGroup Products
     *
     *
     * @apiSuccessExample {json} Success-Response:
     * {"id":1,"product_id":"1","path":"http:\/\/104.217.253.15:3005\/media\/products\/1\/","size":9169,"type":1}
     *
     *
     * @apiError {String} MODEL_NOT_FOUND product model not found
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
    public function addMedia(Request $request,$product_id)
    {
        try
        {
            if(!$request->hasFile('file'))
                return $this->respondValidationFailed('file not included');
            if(!$request->file('file')->isValid())
                return $this->respondValidationFailed('file not uploaded successfully');


            $user = Auth::user();
            $product = Product::find($product_id);
            if($product == null)
                return $this->respondModelNotFound();
            if($product->creator_id != $user->id)
                return $this->respondUnauthorized();

            $file = $request->file('file');
            $fileExtension = $file->getClientOriginalExtension();
            $mediaType = Helpers::getFileType($fileExtension);
            if($mediaType == MediaTypes::UNKNOWN)
                return $this->respondValidationFailed('file is not a photo nor a video');
            $filePath = 'media/products/'.$product->id.'/';
            $file->move($filePath);

            $media = new Medium();
            $media->creator_id = $user->id;
            $media->path = $filePath;
            $media->size = $file->getClientSize();
            $media->type = $mediaType;
            $media->save();

            $product->media->attach($media);

            $transformedMedia = Helpers::transformObject($media, new MediumTransformer());
            return $this->respond($transformedMedia);
        }catch (\Exception $ex){
            return $this->respondUnknownException($ex);
        }
    }

    /**
     * Delete product media
     *
     * @api {delete} /products/{product_id}/media/{media_id} Delete Product Media
     * @apiName DeleteProductMedia
     * @apiGroup Products
     *
     *
     * @apiSuccess Status NO_CONTENT: 204
     *
     * @apiError {String} UNAUTHORIZED
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
    public function deleteMedia($product_id, $media_id)
    {
        try
        {
            $user = Auth::user();
            $product = Product::find($product_id);
            if($product == null)
                return $this->respondModelNotFound('product model not found');
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
