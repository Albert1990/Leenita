<?php

use Illuminate\Database\Seeder;
use App\Tag;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tag1= new Tag();
        $tag1->name = 'Sport';
        $tag1->save();

        $tag2 = new Tag();
        $tag2->name = 'Electronic';
        $tag2->save();

        $tag3 = new Tag();
        $tag3->name = 'Mobile Phones';
        $tag3->save();
    }
}
