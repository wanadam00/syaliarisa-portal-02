<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StandardApplication;

class StandardApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StandardApplication::truncate();
        StandardApplication::insert([
            [
                'title' => 'ISO 9001',
                'description' => 'Quality management systems.',
                'logo' => null,
                'link' => 'https://example.com/iso-9001',
                'is_visible' => true,
            ],
            [
                'title' => 'ISO 14001',
                'description' => 'Environmental management systems.',
                'logo' => null,
                'link' => 'https://example.com/iso-14001',
                'is_visible' => true,
            ],
        ]);
    }
}
