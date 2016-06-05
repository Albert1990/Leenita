<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CountriesTableSeeder::class);
        $this->call(PackagesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(BrandsTableSeeder::class);
        $this->call(LanguagesTableSeeder::class);
        $this->call(TermsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(TagsTableSeeder::class);
    }
}
