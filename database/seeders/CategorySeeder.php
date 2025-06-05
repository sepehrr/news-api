<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Technology' => 'Latest tech news and updates',
            'Science' => 'Scientific discoveries and research',
            'Business' => 'Business and finance news',
            'Health' => 'Health and medical news',
            'Entertainment' => 'Entertainment and media updates',
            'Sports' => 'Sports news and updates',
            'Politics' => 'Political news and analysis',
            'Environment' => 'Environmental news and climate updates',
        ];

        foreach ($categories as $name => $description) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $description,
            ]);
        }
    }
}
