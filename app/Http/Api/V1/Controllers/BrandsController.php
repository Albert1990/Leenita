<?php

namespace App\Http\Api\V1\Controllers;

use App\Branch;
use App\Brand;
use App\BrandContact;
use App\Containers\ProductContainer;
use App\Containers\TermContainer;
use App\Http\Enums\ContactTypes;
use App\Http\Helpers;
use App\Http\Enums\HttpStatus;
use App\Http\Enums\ValidationRules;
use App\Medium;
use App\Product;
use App\Tag;
use App\Transformers\BranchTransformer;
use App\Transformers\BrandTransformer;
use App\Transformers\MediumTransformer;
use App\Transformers\ProductTransformer;
use App\Transformers\TagTransformer;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BrandsController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @api {get} /brands Request Brands List
     * @apiName GetBrands
     * @apiGroup Brands
     * @apiSuccessExample {json} Success-Response:
     * [{"id":1,"name":"Samsung","location":"0,0","logo":"http:\/\/104.217.253.15:3005\/images\/brands\/Samsung_Logo.png","contacts":[]},{"id":2,"name":"Adidas","location":"0,0","logo":"http:\/\/104.217.253.15:3005\/images\/brands\/adidas_Logo.png","contacts":[]},{"id":3,"name":"Moulinex","location":"0,0","logo":"http:\/\/104.217.253.15:3005\/images\/brands\/moulinex.jpg","contacts":[]}]
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
    public function index()
    {
        try {
            $brands = Brand::all();
            $transformedBrands = Helpers::transformArray($brands, new BrandTransformer());
            return $this->respond($transformedBrands);
        }catch (\Exception $ex){
            return $this->respondUnknownException($ex);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @api {post} /brands Store Brand
     * @apiName StoreBrand
     * @apiGroup Brands
     *
     * @apiParam {String} name brand name
     * @apiParam {String} logo logo path (optional)
     * @apiParam {Number} location brand location "longitude,latitude" (optional)
     * @apiParam {String} phone (optional)
     * @apiParam (String) email (optional)
     * @apiParam (String) mobile_number {optional}
     * @apiParam {String} website {optional}
     *
     *
     * @apiSuccessExample {json} Success-Response:
     * {"id":4,"name":"zozo","location":"0,0","logo":"http:\/\/104.217.253.15:3005\/images\/default.png","contacts":[{"id":1,"type":1,"value":"+96176309032"},{"id":2,"type":3,"value":"samer.shatta@gmail.com"}]}
     *
     * @apiError {String} VALIDATION_ERROR validation failed
     * @apiError {String} UNKNOWN_EXCEPTION
     *
     * @apiErrorExample {json} Error-Response:
     *   {
     *   "error": "UNKNOWN_EXCEPTION",
     *   "info": "exception info"
     *   }
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => ValidationRules::NAME,
            'phone' => ValidationRules::PHONE,
            'email' => ValidationRules::EMAIL,
            'mobile_number' => ValidationRules::PHONE,
            'website' => ValidationRules::WEBSITE,
        ]);
        if($validator->fails())
            return $this->respondValidationFailed($validator->errors());

        try
        {
            $user = Auth::user();

            $brand = new Brand();
            $brand->creator_id=$user->id;
            $brand->name=$request['name'];
            $brand->logo=$request->input('logo','');
            $brand->location_x=0;
            $brand->location_y=0;
            $brandLocation = $request->input('location',false) != false ? explode(',',$request['location']) : false;
            if($brandLocation != null && count($brandLocation) ==2 )
            {
                $brand->location_x = $brandLocation[0];
                $brand->location_y = $brandLocation[1];
            }
            $brand->save();

            if($request->input('phone',false)) {
                $phoneContact = new BrandContact();
                $phoneContact->type = ContactTypes::PHONE;
                $phoneContact->value = $request['phone'];
                $brand->contacts()->save($phoneContact);
            }

            if($request->input('email',false)) {
                $emailContact = new BrandContact();
                $emailContact->type = ContactTypes::EMAIL;
                $emailContact->value = $request['email'];
                $brand->contacts()->save($emailContact);
            }

            if($request->input('mobile_number',false)) {
                $mobileNumberContact = new BrandContact();
                $mobileNumberContact->type = ContactTypes::MOBILE;
                $mobileNumberContact->value = $request['mobile_number'];
                $brand->contacts()->save($mobileNumberContact);
            }

            if($request->input('website',false)) {
                $websiteContact = new BrandContact();
                $websiteContact->type = ContactTypes::WEBSITE;
                $websiteContact->value = $request['website'];
                $brand->contacts()->save($websiteContact);
            }


            $transformedBrand = Helpers::transformObject($brand,new BrandTransformer());
            return $this->respond($transformedBrand);
        }catch (\Exception $ex){
            return $this->respondUnknownException($ex);
        }
    }

    /**
     * Display the specified resource.
     *
     * @api {get} /brands/{id} Request Brand Info
     * @apiName GetBrand
     * @apiGroup Brands
     *
     * @apiParam {Number} id brand unique id
     *
     *
     * @apiSuccessExample {json} Success-Response:
     * {"id":2,"name":"Adidas","location":"0,0","logo":"http:\/\/104.217.253.15:3005\/images\/brands\/adidas_Logo.png","contacts":[],"tags":[{"id":1,"name":"Sport"}]}
     *
     * @apiError {String} MODEL_NOT_FOUND brand model not found
     * @apiError {String} UNKNOWN_EXCEPTION
     *
     * @apiErrorExample {json} Error-Response:
     *   {
     *   "error": "UNKNOWN_EXCEPTION",
     *   "info": "exception info"
     *   }
     */
    public function show($id)
    {
        try {
            $brand = Brand::find($id);
            if ($brand == null)
                return $this->respondModelNotFound();

            $transformedBrand = Helpers::transformObject($brand, new BrandTransformer());
            return $this->respond($transformedBrand);
        }catch (\Exception $ex){
            return $this->respondUnknownException($ex);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @api {put} /brands/{id} Update Brand
     * @apiName UpdateBrand
     * @apiGroup Brands
     *
     * @apiParam {String} name brand name
     * @apiParam {String} logo logo path (optional)
     * @apiParam {Number} location brand location "longitude,latitude" (optional)
     *
     *
     * @apiSuccessExample {json} Success-Response:
     * {"id":1,"name":"zozo","location":"0,0","logo":"http:\/\/104.217.253.15:3005\/images\/brands\/Samsung_Logo.png","contacts":[]}
     *
     * @apiError {String} VALIDATION_ERROR validation failed
     * @apiError {String} UNAUTHORIZED
     * @apiError {String} MODEL_NOT_FOUND brand model not found
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
        $validator = Validator::make($request->all(),[
            'name' => ValidationRules::NAME,
        ]);
        if($validator->fails())
            return $this->respondValidationFailed($validator->errors());

        try
        {
            $brand = Brand::find($id);
            if($brand == null)
                return $this->respondModelNotFound();

            $user = Auth::user();
            if($brand->creator_id != $user->id)
                return $this->respondUnauthorized();

            $brand->name=$request['name'];
            if($request->input('logo',false))
                $brand->logo=$request['logo'];
            if($request->input('location',false))
            {
                $brandLocation = explode(',',$request['location']);
                if($brandLocation != null && count($brandLocation) == 2)
                {
                    $brand->location_x = $brandLocation[0];
                    $brand->location_y = $brandLocation[1];
                }
            }
            $brand->save();

            $transformedBrand = Helpers::transformObject($brand,new BrandTransformer());
            return $this->respond($transformedBrand);
        }catch (\Exception $ex){
            return $this->respondUnknownException($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @api {delete} /brands/{id} Delete Brand
     * @apiName DeleteBrand
     * @apiGroup Brands
     *
     * @apiSuccess {Number} Status 204 (NO_CONTENT)
     *
     * @apiError {String} MODEL_NOT_FOUND brand model not found
     * @apiError {String} UNAUTHORIZED
     * @apiError {String} UNKNOWN_EXCEPTION unknown exception
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
        try {
            $user = Auth::user();
            $brand = Brand::find($id);
            if ($brand == null)
                return $this->respondModelNotFound();
            if($brand->creator_id != $user->id)
                return $this->respondUnauthorized();

            $brand->delete();
            return $this->respond(null,HttpStatus::NO_CONTENT);
        }catch (\Exception $ex){
            return $this->respondUnknownException($ex);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @api {get} /brands/{brand_id}/products/{language_id?} Request Brand Products
     * @apiName GetBrandProducts
     * @apiGroup Brands
     *
     *
     * @apiSuccessExample {json} Success-Response:
     * [{"id":2,"name":"Toshiba Tv","description":"It's great tv form Toshiba","price":100,"brand":{"id":1,"name":"Samsung","location":"0,0","logo":"http:\/\/104.217.253.15:3005\/images\/brands\/Samsung_Logo.png","contacts":[]},"media":[],"term":{"id":1,"brand_id":1,"title":"\u0627\u0644\u0634\u0631\u0648\u0637","content":"\u0627\u062d\u0630\u0631 \u0645\u0646 \u0647\u0630\u0647 \u0627\u0644\u0634\u0631\u0648\u0637"}}]
     *
     * @apiError {Sring} UNKNOWN_EXCEPTION unknown exception
     *
     * @apiErrorExample {json} Error-Response:
     *   {
     *   "error": "UNKNOWN_EXCEPTION",
     *   "info": "exception info"
     *   }
     *
     */
    public function products($brand_id,$language_id)
    {
        try {
            $productsContainer = [];
            $products = Product::where('brand_id', $brand_id)->get();
            foreach ($products as $product) {
                $productTranslation = $product->translation($language_id);
                if($productTranslation == null)
                    $productTranslation = $product->translations->first();

                $productTermTranslation = $product->term->translation($language_id);
                if($productTermTranslation == null)
                    $productTermTranslation = $product->term->translations->first();

                $productContainer = new ProductContainer(
                    $product,
                    $productTranslation,
                    new TermContainer(
                        $product->term,
                        $productTermTranslation
                    )
                );
                array_push($productsContainer, $productContainer);
            }
            $transformedProducts = Helpers::transformArray($productsContainer, new ProductTransformer());
            return $this->respond($transformedProducts);
        }catch (\Exception $ex){
            return $this->respondUnknownException($ex);
        }
    }

    /**
     * Display a listing of the brand tags.
     *
     * @api {get} /brands/{brand_id}/tags Request Brand Tags
     * @apiName GetBrandTags
     * @apiGroup Brands
     *
     * @apiSuccessExample {json} Success-Response:
     * [{"id":1,"name":"Sport"}]
     *
     * @apiError {Sring} UNKNOWN_EXCEPTION unknown exception
     *
     * @apiErrorExample {json} Error-Response:
     *   {
     *   "error": "UNKNOWN_EXCEPTION",
     *   "info": "exception info"
     *   }
     *
     */
    public function tags($brand_id)
    {
        try
        {
            $brand = Brand::find($brand_id);
            if($brand == null)
                return $this->respondModelNotFound();

            $brandTags = $brand->tags;
            $transformedTags = Helpers::transformArray($brandTags,new TagTransformer());
            return $this->respond($transformedTags);
        }catch (\Exception $ex){
            return $this->respondUnknownException($ex);
        }
    }

    /**
     * Assign tag to brand
     *
     * @api {post} /brands/{brand_id}/tags Assign Tag
     * @apiName AssignBrandTag
     * @apiGroup Brands
     *
     * @apiSuccess Status 204:NO_CONTENT
     *
     * @apiError {String} UNKNOWN_EXCEPTION unknown exception
     * @apiError {String} MODEL_NOT_FOUND
     * @apiError {String} UNAUTHORIZED
     *
     * @apiErrorExample {json} Error-Response:
     *   {
     *   "error": "UNKNOWN_EXCEPTION",
     *   "info": "exception info"
     *   }
     *
     */
    public function assignTag(Request $request,$brand_id)
    {
        try{
            $validator = Validator::make($request->all(),[
                'tag_id' => ValidationRules::ID,
            ]);
            if($validator->fails())
                return $this->respondValidationFailed($validator->errors());

            $user = Auth::user();
            $brand = Brand::find($brand_id);
            if($brand == null)
                return $this->respondModelNotFound('brand model not found');
            if($brand->creator_id != $user->id)
                return $this->respondUnauthorized();

            $tag = Tag::find($request['tag_id']);
            if($tag == null)
                return $this->respondModelNotFound('tag model not found');

            $brand->tags()->attach($tag);
            return $this->respond(null,HttpStatus::NO_CONTENT);
        }catch (\Exception $ex){
            return $this->respondUnknownException($ex);
        }
    }

    /**
     * Deassign tag from brand
     *
     * @api {delete} /brands/{brand_id}/tags/{tag_id} Assign Tag
     * @apiName DeassignBrandTag
     * @apiGroup Brands
     *
     * @apiSuccess Status 204:NO_CONTENT
     *
     * @apiError {String} UNKNOWN_EXCEPTION unknown exception
     * @apiError {String} MODEL_NOT_FOUND
     * @apiError {String} UNAUTHORIZED
     *
     * @apiErrorExample {json} Error-Response:
     *   {
     *   "error": "UNKNOWN_EXCEPTION",
     *   "info": "exception info"
     *   }
     *
     */
    public function deassignTag($brand_id,$tag_id)
    {
        try{
            $user = Auth::user();
            $brand = Brand::find($brand_id);
            if($brand == null)
                return $this->respondModelNotFound('brand model not found');
            if($brand->creator_id != $user->id)
                return $this->respondUnauthorized();

            $tag = Tag::find($tag_id);
            if($tag == null)
                return $this->respondModelNotFound('tag model not found');

            $brand->tags()->detach($tag);
            return $this->respond(null,HttpStatus::NO_CONTENT);
        }catch (\Exception $ex){
            return $this->respondUnknownException($ex);
        }
    }

    /**
     * Display brand branches
     *
     * @api {get} /brands/{brand_id}/branches Request Brand Branches
     * @apiName GetBrandBranches
     * @apiGroup Brands
     *
     * @apiSuccessExample {json} Success-Response:
     * [{"name":"Mazraa","location":"0,0","phone":"+96176309032","fax":"","email":"","website":""},{"name":"Mazraa","location":"0,0","phone":"+96176309032","fax":"","email":"","website":""}]
     *
     * @apiError {String} UNKNOWN_EXCEPTION unknown exception
     * @apiError {String} MODEL_NOT_FOUND
     *
     * @apiErrorExample {json} Error-Response:
     *   {
     *   "error": "UNKNOWN_EXCEPTION",
     *   "info": "exception info"
     *   }
     *
     */
    public function branches($brand_id)
    {
        try {
            $brand = Brand::find($brand_id);
            if($brand == null)
                return $this->respondModelNotFound('brand model not found');
            $branches = $brand->branches;
            $transformedBranches = Helpers::transformArray($branches, new BranchTransformer());
            return $this->respond($transformedBranches);
        }catch (\Exception $ex){
            return $this->respondUnknownException($ex);
        }
    }

    /**
     * Add branch to brand
     *
     * @api {post} /brands/{brand_id}/branches Add Branch
     * @apiName AddBranch
     * @apiGroup Brands
     *
     * @apiSuccessExample {json} Success-Response:
     * {"name":"Mazraa","location":"0,0","phone":"+96176309032","fax":"","email":"","website":""}
     *
     * @apiError {String} UNKNOWN_EXCEPTION unknown exception
     * @apiError {String} UNAUTHORIZED
     * @apiError {String} MODEL_NOT_FOUND
     *
     * @apiErrorExample {json} Error-Response:
     *   {
     *   "error": "UNKNOWN_EXCEPTION",
     *   "info": "exception info"
     *   }
     *
     */
    public function addBranch(Request $request,$brand_id){
        try
        {
            $validator = Validator::make($request->all(),[
                'name' => ValidationRules::NAME,
                'phone' => ValidationRules::PHONE,
                'email' => ValidationRules::EMAIL,
                'mobile_number' => ValidationRules::PHONE,
                'website' => ValidationRules::WEBSITE,
            ]);
            if($validator->fails())
                return $this->respondValidationFailed($validator->errors());

            $user = Auth::user();
            $brand = Brand::find($brand_id);
            if($brand == null)
                return $this->respondModelNotFound('brand model not found');
            if($brand->creator_id != $user->id)
                return $this->respondUnauthorized();

            $branch = new Branch();
            $branch->brand_id = $brand->id;
            $branch->name = $request['name'];
            $branch->location_x=0;
            $branch->location_y=0;
            $branchLocation = $request->input('location',false) != false ? explode(',',$request['location']) : false;
            if($branchLocation != null && count($branchLocation) ==2 )
            {
                $brand->location_x = $branchLocation[0];
                $brand->location_y = $branchLocation[1];
            }
            $branch->phone = $request->input('phone','');
            $branch->fax = $request->input('fax','');
            $branch->email = $request->input('email','');
            $branch->website = $request->input('website','');
            $branch->save();

            $transformedBranch = Helpers::transformObject($branch, new BranchTransformer());
            return $this->respond($transformedBranch);
        }catch (\Exception $ex){
            return $this->respondUnknownException($ex);
        }
    }

    /**
     * delete branch from brand
     *
     * @api {delete} /brands/{brand_id}/branches Add Branch
     * @apiName DeleteBranch
     * @apiGroup Brands
     *
     * @apiSuccess Status 204:NO_CONTENT
     *
     * @apiError {String} UNKNOWN_EXCEPTION unknown exception
     * @apiError {String} UNAUTHORIZED
     * @apiError {String} MODEL_NOT_FOUND
     *
     * @apiErrorExample {json} Error-Response:
     *   {
     *   "error": "UNKNOWN_EXCEPTION",
     *   "info": "exception info"
     *   }
     *
     */
    public function deleteBranch($brand_id,$branch_id){
        try
        {
            $brand = Brand::find($branch_id);
            if($brand == null)
                return $this->respondModelNotFound('brand model not found');
            $user = Auth::user();
            if($brand->creator_id != $user->id)
                return $this->respondUnauthorized();

            $branch = Branch::find($branch_id);
            if($branch == null)
                return $this->respondModelNotFound('branch model not found');

            $branch->delete();
            return $this->respond(null,HttpStatus::NO_CONTENT);
        }catch (\Exception $ex){
            return $this->respondUnknownException($ex);
        }
    }

    /**
     * List of brand media
     *
     * @api {get} /brands/{brand_id}/media Request Brand Media
     * @apiName BrandMedia
     * @apiGroup Brands
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
    public function media($brand_id)
    {
        try {
            $brand = Brand::find($brand_id);
            if($brand == null)
                return $this->respondModelNotFound();
            $media = $brand->media;
            $transformedMedia = Helpers::transformArray($media, new MediumTransformer());
            return $this->respond($transformedMedia);
        }catch (\Exception $ex){
            return $this->respondUnknownException($ex);
        }
    }

    /**
     * Add brand media
     *
     * @api {post} /brands/{brand_id}/media Store Brand Media
     * @apiName AddBrandMedia
     * @apiGroup Brands
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
    public function addMedia(Request $request,$brand_id)
    {
        try
        {
            if(!$request->hasFile('file'))
                return $this->respondValidationFailed('file not included');
            if(!$request->file('file')->isValid())
                return $this->respondValidationFailed('file not uploaded successfully');


            $user = Auth::user();
            $brand = Brand::find($brand_id);
            if($brand == null)
                return $this->respondModelNotFound();
            if($brand->creator_id != $user->id)
                return $this->respondUnauthorized();

            $file = $request->file('file');
            $fileExtension = $file->getClientOriginalExtension();
            $mediaType = Helpers::getFileType($fileExtension);
            if($mediaType == MediaTypes::UNKNOWN)
                return $this->respondValidationFailed('file is not a photo nor a video');
            $filePath = 'media/products/'.$brand->id.'/';
            $file->move($filePath);

            $media = new Medium();
            $media->creator_id = $user->id;
            $media->path = $filePath;
            $media->size = $file->getClientSize();
            $media->type = $mediaType;
            $media->save();

            $brand->media->attach($media);

            $transformedMedia = Helpers::transformObject($media, new MediumTransformer());
            return $this->respond($transformedMedia);
        }catch (\Exception $ex){
            return $this->respondUnknownException($ex);
        }
    }

    /**
     * Delete brand media
     *
     * @api {delete} /brands/{brand_id}/media/{media_id} Delete Brand Media
     * @apiName DeleteBrandMedia
     * @apiGroup Brands
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
    public function deleteMedia($brand_id, $media_id)
    {
        try
        {
            $user = Auth::user();
            $brand = Brand::find($brand_id);
            if($brand == null)
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
