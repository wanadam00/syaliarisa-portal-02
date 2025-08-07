<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomeSection;

class HomeSectionSeeder extends Seeder
{
    public function run(): void
    {
        HomeSection::truncate();
        HomeSection::insert([
            [
                'title' => 'Welcome',
                'content' => 'Welcome to our company portal!',
                'is_visible' => true,
            ],
            [
                'title' => 'Our Values',
                'content' => 'Integrity, Innovation, Excellence.',
                'is_visible' => true,
            ],
        ]);
    }
}
