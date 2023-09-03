<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        // создание пользователя
        \DB::table('users')->insert([
            'name'  => $faker->name(),
            'email' => $faker->email(),
            'password' => \Hash::make('12345678'),
            'role' => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // создание библиотекаря
        \DB::table('users')->insert([
            'name'     => $faker->name(),
            'email'    => $faker->email(),
            'password' => \Hash::make('12345678'),
            'role' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
