<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanyInfo;

class CompanyInfoSeeder extends Seeder
{
    public function run(): void
    {
        CompanyInfo::truncate();
        CompanyInfo::create([
            'background' => 'Leading provider of integrated solutions.',
            'vision' => 'To be the most trusted company in our field.',
            'mission' => 'Delivering excellence and innovation.',
            'organization_structure' => 'CEO > Managers > Staff',
            'is_visible' => true,
        ]);
    }
}
