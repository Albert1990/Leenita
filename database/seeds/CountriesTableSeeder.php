<?php

use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country=new \App\Country();
        $country->iso2='SY';
        $country->short_name='Syria';
        $country->long_name='Syrian Arab Republic';
        $country->iso3='SYR';
        $country->numcode='760';
        $country->un_member='yes';
        $country->calling_code='963';
        $country->cctld='.sy';
        $country->save();
    }
}
