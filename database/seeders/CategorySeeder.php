<?php

namespace Database\Seeders;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ["name"=>"HAIR COLOR"],
            ["name"=>"HAIR CARE"],
            ["name"=>"HAIR STYLING"],
            ["name"=>"SALON TREATMENT"],
        ];
        Category::insert($categories);
        User::create(["name"=>"radiant","email"=>"radiant@gmail.com","password"=>\Hash::make('adminRdt@09')]);
        User::create(["name"=>"admin","email"=>"amadordev@gmail.com","password"=>\Hash::make('@admin09@')]);
        User::create(["name"=>"dev","email"=>"admin@gmail.com","password"=>\Hash::make('amador@1234Lsg')]);
    }
}
