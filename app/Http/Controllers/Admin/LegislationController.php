<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Legislation;
use Illuminate\Http\Request;

class LegislationController extends Controller
{
    public function index()
    {
        $legislations = Legislation::all();
        return view('admin.legislations.index', compact('legislations'));
    }

    public function create()
    {
        return view('admin.legislations.create');
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
        return view('admin.legislations.edit', compact('legislation'));
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
