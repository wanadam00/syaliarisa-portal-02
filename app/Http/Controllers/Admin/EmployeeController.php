<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('position')->get()->map(function ($employee) {
            // expose photo url
            $employee->photo = $employee->photo
                ? asset('storage/' . $employee->photo)
                : null;

            // get loaded relation explicitly to avoid collision with legacy 'position' string column
            $posRelation = $employee->getRelationValue('position');
            if ($posRelation && is_object($posRelation)) {
                $employee->position = $posRelation->name;
            } else {
                // fall back to legacy string value if present
                $employee->position = is_string($employee->position) ? $employee->position : null;
            }

            return $employee;
        });

        return Inertia::render('Admin/Employee/Index', [
            'employees' => $employees,
        ]);
    }

    public function create(Employee $employee)
    {
        // provide positions list for dropdown and inline add
        $positions = Position::orderBy('rank', 'asc')->get();
        return Inertia::render('Admin/Employee/Create', [
            'employee' => $employee,
            'positions' => $positions,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'position_id' => 'required|exists:positions,id',
            'department' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_visible' => 'boolean',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('employees', 'public');
            $data['photo'] = $path;
        } else {
            unset($data['photo']); // 🚀 prevents overwriting with null
        }

        // Ensure we only store allowed fields
        $employeeData = [
            'name' => $data['name'],
            'position_id' => $data['position_id'],
            'department' => $data['department'],
            'is_visible' => $request->boolean('is_visible'),
        ];

        if (isset($data['photo'])) {
            $employeeData['photo'] = $data['photo'];
        }

        Employee::create($employeeData);
        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully.');
    }

    public function edit(Employee $employee)
    {
        $positions = Position::orderBy('rank', 'asc')->get();
        return Inertia::render('Admin/Employee/Edit', [
            'employee' => $employee,
            'positions' => $positions,
        ]);
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'position_id' => 'required|exists:positions,id',
            'department' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_visible' => 'boolean',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('employees', 'public');
            $data['photo'] = $path;
        } else {
            unset($data['photo']); // 🚀 prevents overwriting with null
        }

        $employeeData = [
            'name' => $data['name'],
            'position_id' => $data['position_id'],
            'department' => $data['department'],
            'is_visible' => $request->boolean('is_visible'),
        ];

        if (isset($data['photo'])) {
            $employeeData['photo'] = $data['photo'];
        }

        $employee->update($employeeData);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted successfully.');
    }
}
