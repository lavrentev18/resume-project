<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class FillBooks extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $wordForNovel = $faker->word();
        $wordForDetective = $faker->word();
        $wordForFantastic = $faker->word();
        \DB::table('books')->insert([
            [
                'name' => $wordForNovel,
                'author'  => $faker->name(),
                 'slug' => $wordForNovel,
                'genre' => 'novel',
                'publisher' => $faker->word(),
                'description' => $faker->text(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => $wordForDetective,
                'author'  => $faker->name(),
                'slug' => $wordForDetective,
                'genre' => 'detective',
                'publisher' => $faker->word(),
                'description' => $faker->text(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => $wordForFantastic,
                'author'  => $faker->name(),
                'slug' => $wordForFantastic,
                'genre' => 'fantastic',
                'publisher' => $faker->word(),
                'description' => $faker->text(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

    }
}
