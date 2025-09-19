<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Legislation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LegislationController extends Controller
{
    public function index()
    {
        $legislations = Legislation::all();
        // return route('admin.legislations.index', compact('legislations'));
        return Inertia::render('Admin/Legislation/Index', [
            'legislations' => $legislations,
        ]);
    }

    public function create()
    {
        // return route('admin.legislations.create');
        return Inertia::render('Admin/Legislation/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'details' => 'nullable|string',
            'image' => 'nullable|image',
            'link' => 'nullable|url',
            'is_visible' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('legislations', 'public');
        }

        Legislation::create($validated);

        return redirect()->route('admin.legislations.index')->with('success', 'Legislation created successfully.');
    }

    public function edit(Legislation $legislation)
    {
        // return route('admin.legislations.edit', compact('legislation'));
        return Inertia::render('Admin/Legislation/Edit', [
            'legislation' => $legislation,
        ]);
    }

    public function update(Request $request, Legislation $legislation)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'details' => 'nullable|string',
            'image' => 'nullable|image',
            'link' => 'nullable|url',
            'is_visible' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('legislations', 'public');
        }

        $legislation->update($validated);

        return redirect()->route('admin.legislations.index')->with('success', 'Legislation updated successfully.');
    }

    public function destroy(Legislation $legislation)
    {
        $legislation->delete();

        return redirect()->route('admin.legislations.index')->with('success', 'Legislation deleted successfully.');
    }
}
