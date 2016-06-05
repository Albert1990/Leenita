<?php

use Illuminate\Database\Seeder;
use App\Language;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $english=new Language();
        $english->name='English';
        $english->is_rtl=false;
        $english->save();


        $arabic=new Language();
        $arabic->name='Arabic';
        $arabic->is_rtl=true;
        $arabic->save();


        $kurdish=new Language();
        $kurdish->name='Kurdish';
        $kurdish->is_rtl=true;
        $kurdish->save();
    }
}
