<?php

use Illuminate\Database\Seeder;
use App\Term;
use App\Brand;
use App\TermTranslation;
use App\Language;

class TermsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $langs=Language::all();

        $term1 = new Term();
        $term1->brand_id=Brand::first()->id;
        $term1->save();

        $trans2=new TermTranslation();
        $trans2->language_id=$langs[1]->id;
        $trans2->title='Terms & Conditions';
        $trans2->content='hey take care';
        $term1->translations()->save($trans2);

        $trans1=new TermTranslation();
        $trans1->language_id=$langs[0]->id;
        $trans1->title='الشروط';
        $trans1->content='احذر من هذه الشروط';
        $term1->translations()->save($trans1);


    }
}
