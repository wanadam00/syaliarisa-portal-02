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
                'top_details' => 'Welcome to Our Company',
                'top_image' => 'welcome-image.jpg',
                'bottom_details' => 'We provide top-notch services to our clients.',
                'bottom_image' => 'services-image.jpg',
            ],
        ]);
    }
}
