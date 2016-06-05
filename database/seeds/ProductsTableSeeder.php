<?php

use Illuminate\Database\Seeder;
use App\ProductMedia;
use App\Http\MediaTypes;
use App\User;
use App\Xobject;
use App\Http\XobjectTypes;
use App\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brands=\App\Brand::all();
        //1
        $xobject=new Xobject();
        $xobject->type=XobjectTypes::PRODUCT;
        $xobject->save();
        $product=new Product();
        $product->xobject_id=$xobject->id;
        $product->brand_id=$brands[0]->id;
        $product->name='Smart Curve Tv';
        $product->price=230;
        $product->description='its a great smart tv that can be
        controlled using your hand gesture';
        $product->save();
        $productMedia= new ProductMedia();
        $productMedia->product_id=$product->id;
        $productMedia->path='images/products/smart_tv1.jpg';
        $productMedia->type=MediaTypes::IMAGE;
        $productMedia->save();
        $productMedia= new ProductMedia();
        $productMedia->product_id=$product->id;
        $productMedia->path='images/products/smart_tv2.jpg';
        $productMedia->type=MediaTypes::IMAGE;
        $productMedia->save();
        $productMedia= new ProductMedia();
        $productMedia->product_id=$product->id;
        $productMedia->path='images/products/smart_tv3.jpg';
        $productMedia->type=MediaTypes::IMAGE;
        $productMedia->save();
        $productMedia= new ProductMedia();
        $productMedia->product_id=$product->id;
        $productMedia->path='images/products/smart_tv4.jpg';
        $productMedia->type=MediaTypes::IMAGE;
        $productMedia->save();

        //2
        $xobject=new \App\Xobject();
        $xobject->type=\App\Http\XobjectTypes::PRODUCT;
        $xobject->save();
        $product=new \App\Product();
        $product->xobject_id=$xobject->id;
        $product->brand_id=$brands[0]->id;
        $product->name='Galaxy S7';
        $product->price=820;
        $product->description="It's not just a new phone. It brings a new way of thinking about what a phone can do. You defined the possibilities and we redefined the phone. The Galaxy S7 and S7 edge. Rethink what a phone can do.";
        $product->save();
        $productMedia= new ProductMedia();
        $productMedia->product_id=$product->id;
        $productMedia->path='images/products/s7_1.png';
        $productMedia->type=MediaTypes::IMAGE;
        $productMedia->save();
        $productMedia= new ProductMedia();
        $productMedia->product_id=$product->id;
        $productMedia->path='images/products/s7_2.png';
        $productMedia->type=MediaTypes::IMAGE;
        $productMedia->save();
        $productMedia= new ProductMedia();
        $productMedia->product_id=$product->id;
        $productMedia->path='images/products/s7_3.png';
        $productMedia->type=MediaTypes::IMAGE;
        $productMedia->save();
        $productMedia= new ProductMedia();
        $productMedia->product_id=$product->id;
        $productMedia->path='images/products/s7_4.png';
        $productMedia->type=MediaTypes::IMAGE;
        $productMedia->save();

        //3
        $xobject=new \App\Xobject();
        $xobject->type=\App\Http\XobjectTypes::PRODUCT;
        $xobject->save();
        $product=new \App\Product();
        $product->xobject_id=$xobject->id;
        $product->brand_id=$brands[0]->id;
        $product->name='Gear S2';
        $product->price=500;
        $product->description="In a beauiful partnership, Alessandro Mendini brings his taste, humor and sense of colour to the Gear S2. The result is a range of watch faces and watchbands that completes your personal style.";
        $product->save();
        $productMedia= new ProductMedia();
        $productMedia->product_id=$product->id;
        $productMedia->path='images/products/s2_1.jpg';
        $productMedia->type=MediaTypes::IMAGE;
        $productMedia->save();
        $productMedia= new ProductMedia();
        $productMedia->product_id=$product->id;
        $productMedia->path='images/products/s2_2.jpg';
        $productMedia->type=MediaTypes::IMAGE;
        $productMedia->save();
        $productMedia= new ProductMedia();
        $productMedia->product_id=$product->id;
        $productMedia->path='images/products/s2_3.jpg';
        $productMedia->type=MediaTypes::IMAGE;
        $productMedia->save();
        $productMedia= new ProductMedia();
        $productMedia->product_id=$product->id;
        $productMedia->path='images/products/s2_4.jpg';
        $productMedia->type=MediaTypes::IMAGE;
        $productMedia->save();

        //4
        $xobject=new \App\Xobject();
        $xobject->type=\App\Http\XobjectTypes::PRODUCT;
        $xobject->save();
        $product=new \App\Product();
        $product->xobject_id=$xobject->id;
        $product->brand_id=$brands[0]->id;
        $product->name='Galaxy Tab S2';
        $product->price=370;
        $product->description="Exceptionally Lightweight and Slim
Increased Productivity
Octa-Core 1.9 GHz, 1.3 GHz, 9.7 inch Display, 8 MP Rear Camera+2.1 MP Front Camera";
        $product->save();
        $productMedia= new ProductMedia();
        $productMedia->product_id=$product->id;
        $productMedia->path='images/products/s2_tab_1.jpg';
        $productMedia->type=MediaTypes::IMAGE;
        $productMedia->save();
        $productMedia= new ProductMedia();
        $productMedia->product_id=$product->id;
        $productMedia->path='images/products/s2_tab_2.jpg';
        $productMedia->type=MediaTypes::IMAGE;
        $productMedia->save();
        $productMedia= new ProductMedia();
        $productMedia->product_id=$product->id;
        $productMedia->path='images/products/s2_tab_3.jpg';
        $productMedia->type=MediaTypes::IMAGE;
        $productMedia->save();
        $productMedia= new ProductMedia();
        $productMedia->product_id=$product->id;
        $productMedia->path='images/products/s2_tab_4.jpg';
        $productMedia->type=MediaTypes::IMAGE;
        $productMedia->save();

        //5
        $xobject=new \App\Xobject();
        $xobject->type=\App\Http\XobjectTypes::PRODUCT;
        $xobject->save();
        $product=new \App\Product();
        $product->xobject_id=$xobject->id;
        $product->brand_id=$brands[0]->id;
        $product->name='Wireless Charger';
        $product->price=70;
        $product->description="Greater convenience and quicker charging speed. Boost your power at lightning pace –even without the wires.";
        $product->save();


        //adidas products
        //6
        $xobject=new Xobject();
        $xobject->type=XobjectTypes::PRODUCT;
        $xobject->save();
        $product=new Product();
        $product->xobject_id=$xobject->id;
        $product->brand_id=$brands[1]->id;
        $product->name='Perfume';
        $product->price=130;
        $product->description='great perfume';
        $product->save();
        $productMedia= new ProductMedia();
        $productMedia->product_id=$product->id;
        $productMedia->path='images/products/adidas_perfume.JPG';
        $productMedia->type=MediaTypes::IMAGE;
        $productMedia->save();
        $productMedia= new ProductMedia();
        $productMedia->product_id=$product->id;
        $productMedia->path='images/products/adidas_perfume1.JPG';
        $productMedia->type=MediaTypes::IMAGE;
        $productMedia->save();

        //7
        $xobject=new Xobject();
        $xobject->type=XobjectTypes::PRODUCT;
        $xobject->save();
        $product=new Product();
        $product->xobject_id=$xobject->id;
        $product->brand_id=$brands[1]->id;
        $product->name='Football';
        $product->price=110;
        $product->description="
You landed on adidas' offers and promotions page where you will find all our special deals and discounts. Keep coming back all season long to see our latest specials.";
        $product->save();
        $productMedia= new ProductMedia();
        $productMedia->product_id=$product->id;
        $productMedia->path='images/products/ball.jpg';
        $productMedia->type=MediaTypes::IMAGE;
        $productMedia->save();
        $productMedia= new ProductMedia();
        $productMedia->product_id=$product->id;
        $productMedia->path='images/products/ball1.jpg';
        $productMedia->type=MediaTypes::IMAGE;
        $productMedia->save();

        //8
        $xobject=new Xobject();
        $xobject->type=XobjectTypes::PRODUCT;
        $xobject->save();
        $product=new Product();
        $product->xobject_id=$xobject->id;
        $product->brand_id=$brands[1]->id;
        $product->name='Shoes';
        $product->price=100;
        $product->description='sportage shoes for playing football';
        $product->save();
        $productMedia= new ProductMedia();
        $productMedia->product_id=$product->id;
        $productMedia->path='images/products/shoes1.jpg';
        $productMedia->type=MediaTypes::IMAGE;
        $productMedia->save();
        $productMedia= new ProductMedia();
        $productMedia->product_id=$product->id;
        $productMedia->path='images/products/shoes2.jpg';
        $productMedia->type=MediaTypes::IMAGE;
        $productMedia->save();

        //molinux
        //9
        $xobject=new Xobject();
        $xobject->type=XobjectTypes::PRODUCT;
        $xobject->save();
        $product=new Product();
        $product->xobject_id=$xobject->id;
        $product->brand_id=$brands[2]->id;
        $product->name='Blinder';
        $product->price=100;
        $product->description='Super Blinder for all types of foods';
        $product->save();
        $productMedia= new ProductMedia();
        $productMedia->product_id=$product->id;
        $productMedia->path='images/products/blender1.jpg';
        $productMedia->type=MediaTypes::IMAGE;
        $productMedia->save();
        $productMedia= new ProductMedia();
        $productMedia->product_id=$product->id;
        $productMedia->path='images/products/blender2.jpg';
        $productMedia->type=MediaTypes::IMAGE;
        $productMedia->save();

        //10
        $xobject=new Xobject();
        $xobject->type=XobjectTypes::PRODUCT;
        $xobject->save();
        $product=new Product();
        $product->xobject_id=$xobject->id;
        $product->brand_id=$brands[2]->id;
        $product->name='Chooper';
        $product->price=70;
        $product->description='Great Chopper for all types of foods';
        $product->save();
        $productMedia= new ProductMedia();
        $productMedia->product_id=$product->id;
        $productMedia->path='images/products/chopper1.jpg';
        $productMedia->type=MediaTypes::IMAGE;
        $productMedia->save();
        $productMedia= new ProductMedia();
        $productMedia->product_id=$product->id;
        $productMedia->path='images/products/chopper2.jpg';
        $productMedia->type=MediaTypes::IMAGE;
        $productMedia->save();
    }
}
