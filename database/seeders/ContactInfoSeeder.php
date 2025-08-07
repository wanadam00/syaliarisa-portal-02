<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContactInfo;

class ContactInfoSeeder extends Seeder
{
    public function run(): void
    {
        ContactInfo::truncate();
        ContactInfo::insert([
            [
                'address' => '123 Main St, City, Country',
                'phone' => '+1234567890',
                'email' => 'info@company.com',
                'map_embed' => '<iframe src="https://maps.google.com/..." width="300" height="200"></iframe>',
                'is_visible' => true,
            ],
        ]);
    }
}
