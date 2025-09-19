<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StandardApplication;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StandardApplicationController extends Controller
{
    public function index()
    {
        $standardApplications = StandardApplication::all();
        // return route('admin.standard-applications.index', compact('standardApplications'));
        return Inertia::render('Admin/StandardApplication/Index', [
            'standardApplications' => $standardApplications,
        ]);
    }

    public function create()
    {
        // return route('admin.standard-applications.create');
        return Inertia::render('Admin/StandardApplication/Create');
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
            $validated['logo'] = $request->file('logo')->store('standard-applications', 'public');
        }

        StandardApplication::create($validated);

        return redirect()->route('admin.standard-applications.index')->with('success', 'Standard Application created successfully.');
    }

    public function edit(StandardApplication $standardApplication)
    {
        // return route('admin.standard-applications.edit', compact('standardApplication'));
        return Inertia::render('Admin/StandardApplication/Edit', [
            'standardApplication' => $standardApplication,
        ]);
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
            $validated['logo'] = $request->file('logo')->store('standard-applications', 'public');
        }

        $standardApplication->update($validated);

        return redirect()->route('admin.standard-applications.index')->with('success', 'Standard Application updated successfully.');
    }

    public function destroy(StandardApplication $standardApplication)
    {
        $standardApplication->delete();

        return redirect()->route('admin.standard-applications.index')->with('success', 'Standard Application deleted successfully.');
    }
}
