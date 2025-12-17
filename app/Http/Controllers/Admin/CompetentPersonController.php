<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompetentPerson;
use App\Models\CompanyInfo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompetentPersonController extends Controller
{
    /**
     * Show the list of competent persons
     */
    public function index()
    {
        $competentPersons = CompetentPerson::with('employee')->orderBy('created_at', 'desc')->paginate(10);

        return Inertia::render('Admin/CompetentPersons/Index', [
            'competentPersons' => $competentPersons,
        ]);
    }

    /**
     * Show the form for creating a new competent person
     */
    public function create()
    {
        $employees = \App\Models\Employee::where('is_visible', true)->get();

        return Inertia::render('Admin/CompetentPersons/Form', [
            'competentPerson' => null,
            'employees' => $employees,
        ]);
    }

    /**
     * Show the form for editing a competent person
     */
    public function edit(CompetentPerson $competentPerson)
    {
        $employees = \App\Models\Employee::where('is_visible', true)->get();

        return Inertia::render('Admin/CompetentPersons/Form', [
            'competentPerson' => $competentPerson,
            'employees' => $employees,
        ]);
    }

    /**
     * Store a newly created competent person
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'dosh_numbers' => 'nullable|string',
            'competencies' => 'nullable|string',
            'employee_id' => 'nullable|exists:employees,id',
        ]);

        CompetentPerson::create($validated);

        return redirect()->route('admin.competent-persons.index')->with('success', 'Competent person created successfully.');
    }

    /**
     * Update a competent person
     */
    public function update(Request $request, CompetentPerson $competentPerson)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'dosh_numbers' => 'nullable|string',
            'competencies' => 'nullable|string',
            'employee_id' => 'nullable|exists:employees,id',
            'is_active' => 'nullable|boolean',
        ]);

        $competentPerson->update($validated);

        return redirect()->route('admin.competent-persons.index')->with('success', 'Competent person updated successfully.');
    }

    /**
     * Delete a competent person
     */
    public function destroy(CompetentPerson $competentPerson)
    {
        $competentPerson->delete();

        return redirect()->route('admin.competent-persons.index')->with('success', 'Competent person deleted successfully.');
    }
}
