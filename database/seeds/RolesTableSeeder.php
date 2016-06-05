<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin=new Role();
        $admin->name='administrator';
        $admin->save();

        $product_author=new Role();
        $product_author->name='product_author';
        $product_author->save();


    }
}
