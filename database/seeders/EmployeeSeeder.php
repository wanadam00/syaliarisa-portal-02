<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        Employee::truncate();
        Employee::insert([
            [
                'name' => 'John Doe',
                'position' => 'Chief Executive Officer',
                'department' => 'Management',
                'photo' => null,
                'is_visible' => true,
            ],
            [
                'name' => 'Jane Smith',
                'position' => 'Operations Manager',
                'department' => 'Operations',
                'photo' => null,
                'is_visible' => true,
            ],
            [
                'name' => 'Emily Johnson',
                'position' => 'Finance Staff',
                'department' => 'Finance',
                'photo' => null,
                'is_visible' => true,
            ],
        ]);
    }
}
