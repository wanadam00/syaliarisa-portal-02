<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all()->map(function ($employee) {
            $employee->photo = $employee->photo
                ? asset('storage/' . $employee->photo)
                : null;
            return $employee;
        });

        return Inertia::render('Admin/Employee/Index', [
            'employees' => $employees,
        ]);
    }

    public function create(Employee $employee)
    {
        return Inertia::render('Admin/Employee/Create', [
            'employee' => $employee,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
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

        Employee::create($data);
        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully.');
    }

    public function edit(Employee $employee)
    {
        return Inertia::render('Admin/Employee/Edit', [
            'employee' => $employee,
        ]);
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
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

        $employee->update($data);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted successfully.');
    }
}
