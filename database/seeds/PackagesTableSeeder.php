<?php

use Illuminate\Database\Seeder;

class PackagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Package::create([
            'name'=>'basic',
            'price'=>0
        ]);

        \App\Package::create([
            'name'=>'follow',
            'price'=>0
        ]);

        \App\Package::create([
            'name'=>'offer',
            'price'=>0
        ]);

        \App\Package::create([
            'name'=>'deal',
            'price'=>0
        ]);
    }
}
