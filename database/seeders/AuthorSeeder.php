<?php

namespace Database\Seeders;

use App\Models\Author;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Create 10 authors
        for ($i = 0; $i < 10; $i++) {
            Author::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'bio' => $faker->paragraph,
            ]);
        }
    }
}
