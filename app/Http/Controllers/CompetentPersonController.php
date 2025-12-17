<?php

namespace App\Http\Controllers;

use App\Models\CompetentPerson;
use App\Models\CompanyInfo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompetentPersonController extends Controller
{
    /**
     * Store a newly created competent person
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dosh_numbers' => 'nullable|string',
            'competencies' => 'nullable|string',
            'employee_id' => 'nullable|exists:employees,id',
        ]);

        $competentPerson = CompetentPerson::create($validated);

        return back()->with('success', 'Competent person added successfully.');
    }

    /**
     * Update a competent person
     */
    public function update(Request $request, CompetentPerson $competentPerson)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dosh_numbers' => 'nullable|string',
            'competencies' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $competentPerson->update($validated);

        return back()->with('success', 'Competent person updated successfully.');
    }

    /**
     * Delete a competent person
     */
    public function destroy(CompetentPerson $competentPerson)
    {
        $competentPerson->delete();

        return back()->with('success', 'Competent person deleted successfully.');
    }

    /**
     * Get all competent persons for a company (API endpoint)
     */
    public function getByCompany(CompanyInfo $companyInfo)
    {
        // The competent_people table links to employees. Return active competent people.
        $competentPersons = CompetentPerson::with('employee')->where('is_active', true)->get();

        return response()->json($competentPersons);
    }
}
