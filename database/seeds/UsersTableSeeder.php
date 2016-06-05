<?php

use Illuminate\Database\Seeder;
use App\Http\AccountTypes;
use App\User;
use App\Package;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userAttributes=[
            'country_id'=>1,
            'name'=>'Samer Shatta',
            'mobile_number'=>'+96176309032',
            'email'=>'samer.shatta@gmail.com',
            'location_x'=>0,
            'location_y'=>0,
            'facebook_token'=> '',
            'photo'=>'images/users/samer.jpg',
        ];
        User::create($userAttributes);

        $userAttributes=[
            'country_id'=>1,
            'name'=>'Molham Mahmoud',
            'mobile_number'=>'+963933322595',
            'email'=>'fatherboard@gmail.com',
            'location_x'=>0,
            'location_y'=>0,
            'facebook_token'=> '',
            'photo'=>'images/users/molham.jpg',
        ];
        User::create($userAttributes);
    }
}
