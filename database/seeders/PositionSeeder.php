<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Rank 1: DIRECTOR
        $director = Position::create([
            'name' => 'Director',
            'parent_id' => null,
            'rank' => 1,
            'description' => null,
            'is_visible' => true,
        ]);

        // Rank 2: MANAGER and SECRETARY (reporting to Director)
        $manager = Position::create([
            'name' => 'Manager',
            'parent_id' => $director->id,
            'rank' => 2,
            'description' => null,
            'is_visible' => true,
        ]);

        $secretary = Position::create([
            'name' => 'Secretary',
            'parent_id' => $director->id,
            'rank' => 2, // Same rank as Manager
            'description' => null,
            'is_visible' => true,
        ]);

        // Rank 3: ASSISTANT MANAGERS (reporting to Manager)
        $asst_mgr_site = Position::create([
            'name' => 'Assistant Manager Site',
            'parent_id' => $manager->id,
            'rank' => 3,
            'description' => null,
            'is_visible' => true,
        ]);

        $asst_mgr_admin = Position::create([
            'name' => 'Assistant Manager Admin',
            'parent_id' => $manager->id,
            'rank' => 3, // Same rank as Assistant Manager Site
            'description' => null,
            'is_visible' => true,
        ]);

        // Rank 4: Accountant (reporting to ASSISTANT MANAGERS ADMIN)
        $accountant = Position::create([
            'name' => 'Accountant',
            'parent_id' => $asst_mgr_admin->id, // Primary Parent (Asst Mgr Admin)
            'rank' => 4,
            'description' => null,
            'is_visible' => true,
        ]);

        $admin_exec = Position::create([
            'name' => 'Admin Executive',
            'parent_id' => $asst_mgr_admin->id,
            'rank' => 4,
            'description' => null,
            'is_visible' => true,
        ]);

        $marketing_exec = Position::create([
            'name' => 'Marketing Admin Executive',
            'parent_id' => $asst_mgr_admin->id, // Primary Parent (Asst Mgr Admin)
            'rank' => 4,
            'description' => null,
            'is_visible' => true,
        ]);

        // Rank 5: CHRA Assessor and Admin/Marketing Roles (reporting to Rank 4 positions)
        $chra_assessor = Position::create([
            'name' => 'CHRA Assessor',
            'parent_id' => $asst_mgr_site->id,
            'rank' => 5,
            'description' => null,
            'is_visible' => true,
        ]);

        $hygiene_tech_i = Position::create([
            'name' => 'Hygiene Technician I',
            'parent_id' => $asst_mgr_site->id,
            'rank' => 5,
            'description' => null,
            'is_visible' => true,
        ]);

        $hygiene_tech_ii = Position::create([
            'name' => 'Hygiene Technician II',
            'parent_id' => $asst_mgr_site->id,
            'rank' => 5,
            'description' => null,
            'is_visible' => true,
        ]);

        // Rank 6: ASSISTANT FIELD TECHNICIANS (Reporting to CHRA Assessor)
        $asst_field_tech = Position::create([
            'name' => 'Assistant Field Technician',
            'parent_id' => $chra_assessor->id,
            'rank' => 6,
            'description' => null,
            'is_visible' => true,
        ]);

        // ----------------------------------------------------------------------
        // MATRIX REPORTING (SECONDARY PARENTS)
        // ----------------------------------------------------------------------

        $chra_assessor->parentsPivot()->attach($asst_mgr_admin->id);
        $hygiene_tech_i->parentsPivot()->attach($asst_mgr_admin->id);
        $hygiene_tech_ii->parentsPivot()->attach($asst_mgr_admin->id);

        $asst_field_tech->parentsPivot()->attach($hygiene_tech_i->id);
        $asst_field_tech->parentsPivot()->attach($hygiene_tech_ii->id);
    }
}
