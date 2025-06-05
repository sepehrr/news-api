<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Article;
use App\Models\Category;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Ensure we have categories and authors
        if (Category::count() === 0) {
            $this->call(CategorySeeder::class);
        }

        if (Author::count() === 0) {
            $this->call(AuthorSeeder::class);
        }

        $sources = [
            'TechCrunch',
            'Science Daily',
            'Business Insider',
            'Reuters',
            'Bloomberg',
            'The New York Times',
            'The Guardian',
            'BBC News',
            'CNN',
            'Forbes'
        ];

        // Create 50 articles
        for ($i = 0; $i < 50; $i++) {
            Article::create([
                'title' => $faker->sentence,
                'body' => $faker->paragraphs(rand(3, 6), true),
                'published_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'source' => $faker->randomElement($sources),
                'category_id' => Category::inRandomOrder()->first()->id,
                'author_id' => Author::inRandomOrder()->first()->id,
            ]);
        }
    }
}
