<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Position;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        Employee::truncate();

        // Get all positions
        $positions = Position::all();

        // Sample employees data with departments
        $employeesData = [
            'Director' => [
                ['name' => 'John Doe', 'department' => 'Management'],
            ],
            'Manager' => [
                ['name' => 'Jane Smith', 'department' => 'Operations'],
            ],
            'Secretary' => [
                ['name' => 'Alice Brown', 'department' => 'Administration'],
            ],
            'Assistant Manager Site' => [
                ['name' => 'Bob Wilson', 'department' => 'Site Operations'],
            ],
            'Assistant Manager Admin' => [
                ['name' => 'Carol Davis', 'department' => 'Administration'],
            ],
            'Accountant' => [
                ['name' => 'David Miller', 'department' => 'Finance'],
            ],
            'Admin Executive' => [
                ['name' => 'Eva Garcia', 'department' => 'Administration'],
            ],
            'Marketing Admin Executive' => [
                ['name' => 'Frank Johnson', 'department' => 'Marketing'],
            ],
            'CHRA Assessor' => [
                ['name' => 'Grace Lee', 'department' => 'Assessment'],
            ],
            'Hygiene Technician I' => [
                ['name' => 'Henry Taylor', 'department' => 'Hygiene'],
            ],
            'Hygiene Technician II' => [
                ['name' => 'Ivy Chen', 'department' => 'Hygiene'],
            ],
            'Assistant Field Technician' => [
                ['name' => 'Jack White', 'department' => 'Field Operations'],
            ],
        ];

        foreach ($positions as $position) {
            if (isset($employeesData[$position->name])) {
                foreach ($employeesData[$position->name] as $empData) {
                    Employee::create([
                        'name' => $empData['name'],
                        'position' => $position->name, // Keep string for backward compatibility
                        'position_id' => $position->id,
                        'department' => $empData['department'],
                        'photo' => null,
                        'is_visible' => true,
                    ]);
                }
            }
        }
    }
}
