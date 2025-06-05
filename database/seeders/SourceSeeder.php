<?php

namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    public function run(): void
    {
        $sources = [
            [
                'name' => 'TechCrunch',
                'url' => 'https://techcrunch.com',
                'description' => 'Technology news and analysis'
            ],
            [
                'name' => 'The Verge',
                'url' => 'https://theverge.com',
                'description' => 'Technology, science, art, and culture'
            ],
            [
                'name' => 'Wired',
                'url' => 'https://wired.com',
                'description' => 'Technology, science, culture, and business'
            ],
        ];

        foreach ($sources as $source) {
            Source::create($source);
        }
    }
}
