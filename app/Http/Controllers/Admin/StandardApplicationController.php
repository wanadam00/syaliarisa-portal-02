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
        $standardApplications = StandardApplication::all()->map(function ($app) {
            $app->logo = $app->logo
                ? asset('storage/' . $app->logo)
                : null;
            return $app;
        });
        return Inertia::render('Admin/StandardApplication/Index', [
            'standardApplications' => $standardApplications,
        ]);
    }

    public function create(StandardApplication $standardApplication)
    {
        return Inertia::render('Admin/StandardApplication/Create', [
            'standardApplication' => $standardApplication,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'link' => 'required|url',
            'is_visible' => 'boolean',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('standardapplications', 'public');
            $data['logo'] = $path;
        } else {
            unset($data['logo']); // 🚀 prevents overwriting with null
        }

        StandardApplication::create($data);

        return redirect()->route('admin.standard-applications.index')->with('success', 'Standard Application created successfully.');
    }

    public function edit(StandardApplication $standardApplication)
    {
        return Inertia::render('Admin/StandardApplication/Edit', [
            'standardApplication' => $standardApplication,
        ]);
    }

    public function update(Request $request, StandardApplication $standardApplication)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'link' => 'nullable|url',
            'is_visible' => 'boolean',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('standardapplications', 'public');
            $data['logo'] = $path;
        } else {
            unset($data['logo']); // 🚀 prevents overwriting with null
        }

        $standardApplication->update($data);

        return redirect()->route('admin.standard-applications.index')->with('success', 'Standard Application updated successfully.');
    }

    public function destroy(StandardApplication $standardApplication)
    {
        $standardApplication->delete();
        return redirect()->route('admin.standard-applications.index')->with('success', 'Standard Application deleted successfully.');
    }
}
