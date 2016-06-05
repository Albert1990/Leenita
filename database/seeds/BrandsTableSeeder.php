<?php

use Illuminate\Database\Seeder;
use App\Brand;
use App\User;
use App\BrandContact;
use App\Http\ContactTypes;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users=User::all();
        Brand::create([
            'creator_id'=>$users[0]->id,
            'name'=>'Samsung',
            'logo'=>'images/brands/Samsung_Logo.png',
            'likes'=>1800,
        ]);

        Brand::create([
            'creator_id'=>$users[0]->id,
            'name'=>'Adidas',
            'logo'=>'images/brands/adidas_Logo.png',
            'likes'=>4200,
        ]);

        Brand::create([
            'creator_id'=>$users[1]->id,
            'name'=>'Moulinex',
            'logo'=>'images/brands/moulinex.jpg',
            'likes'=>1100,
        ]);
    }
}
