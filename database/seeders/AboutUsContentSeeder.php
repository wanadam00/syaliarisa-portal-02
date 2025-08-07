<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AboutUsContent;

class AboutUsContentSeeder extends Seeder
{
    public function run(): void
    {
        AboutUsContent::truncate();
        AboutUsContent::insert([
            [
                'section' => 'organization',
                'title' => 'Our Organization',
                'content' => 'We are structured to deliver the best results.',
                'is_visible' => true,
            ],
            [
                'section' => 'standards_application',
                'title' => 'Standards Application',
                'content' => 'We apply international standards in all our work.',
                'is_visible' => true,
            ],
            [
                'section' => 'legislation',
                'title' => 'Legislation',
                'content' => 'We comply with all relevant legislation.',
                'is_visible' => true,
            ],
        ]);
    }
}
