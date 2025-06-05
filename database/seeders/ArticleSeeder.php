<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Ensure we have categories, authors and sources
        if (Category::count() === 0) {
            $this->call(CategorySeeder::class);
        }

        if (Author::count() === 0) {
            $this->call(AuthorSeeder::class);
        }

        if (Source::count() === 0) {
            $this->call(SourceSeeder::class);
        }

        // Create 50 articles
        for ($i = 0; $i < 50; $i++) {
            Article::create([
                'title' => $faker->sentence,
                'body' => $faker->paragraphs(rand(3, 6), true),
                'published_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'source_id' => Source::inRandomOrder()->first()->id,
                'category_id' => Category::inRandomOrder()->first()->id,
                'author_id' => Author::inRandomOrder()->first()->id,
            ]);
        }
    }
}
