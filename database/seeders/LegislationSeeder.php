<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Legislation;

class LegislationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Legislation::truncate();
        Legislation::insert([
            [
                'title' => 'Health and Safety Act',
                'description' => 'Regulations for workplace safety.',
                'type' => 'Act',
                'details' => 'Detailed information about the act.',
                'image' => null,
                'link' => 'https://example.com/health-safety-act',
                'is_visible' => true,
            ],
            [
                'title' => 'Environmental Protection Law',
                'description' => 'Guidelines for environmental conservation.',
                'type' => 'Law',
                'details' => 'Detailed information about the law.',
                'image' => null,
                'link' => 'https://example.com/environmental-law',
                'is_visible' => true,
            ],
        ]);
    }
}
