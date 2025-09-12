<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StandardApplication;
use Illuminate\Http\Request;

class StandardApplicationController extends Controller
{
    public function index()
    {
        $standardApplications = StandardApplication::all();
        return view('admin.standard_applications.index', compact('standardApplications'));
    }

    public function create()
    {
        return view('admin.standard_applications.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'logo' => 'nullable|image',
            'link' => 'nullable|url',
            'is_visible' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('standard_applications', 'public');
        }

        StandardApplication::create($validated);

        return redirect()->route('admin.standard_applications.index')->with('success', 'Standard Application created successfully.');
    }

    public function edit(StandardApplication $standardApplication)
    {
        return view('admin.standard_applications.edit', compact('standardApplication'));
    }

    public function update(Request $request, StandardApplication $standardApplication)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'logo' => 'nullable|image',
            'link' => 'nullable|url',
            'is_visible' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('standard_applications', 'public');
        }

        $standardApplication->update($validated);

        return redirect()->route('admin.standard_applications.index')->with('success', 'Standard Application updated successfully.');
    }

    public function destroy(StandardApplication $standardApplication)
    {
        $standardApplication->delete();

        return redirect()->route('admin.standard_applications.index')->with('success', 'Standard Application deleted successfully.');
    }
}
