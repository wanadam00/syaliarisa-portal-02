<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        Service::truncate();
        Service::insert([
            [
                'type' => 'health_safety',
                'title' => 'Health & Safety Consulting',
                'summary' => 'Expert advice on workplace safety.',
                'details' => 'We provide audits, training, and compliance checks.',
                'image' => null,
                'is_visible' => true,
            ],
            [
                'type' => 'training',
                'title' => 'Professional Training',
                'summary' => 'Upskill your team with our certified courses.',
                'details' => 'On-site and online training available.',
                'image' => null,
                'is_visible' => true,
            ],
            [
                'type' => 'engineering',
                'title' => 'Engineering Solutions',
                'summary' => 'Innovative engineering for your business.',
                'details' => 'Custom solutions for every challenge.',
                'image' => null,
                'is_visible' => true,
            ],
            [
                'type' => 'environmental',
                'title' => 'Environmental Services',
                'summary' => 'Sustainable and eco-friendly services.',
                'details' => 'Environmental audits and consulting.',
                'image' => null,
                'is_visible' => true,
            ],
        ]);
    }
}
