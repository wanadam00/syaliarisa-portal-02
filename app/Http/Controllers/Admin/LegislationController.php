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
        $legislations = Legislation::all()->map(function ($legislation) {
            $legislation->image = $legislation->image
                ? asset('storage/' . $legislation->image)
                : null;
            return $legislation;
        });
        return Inertia::render('Admin/Legislation/Index', [
            'legislations' => $legislations,
        ]);
    }

    public function create(Legislation $legislation)
    {
        return Inertia::render('Admin/Legislation/Create', [
            'legislation' => $legislation,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string|max:255',
            'details' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'link' => 'nullable|url',
            'is_visible' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('legislation', 'public');
            $data['image'] = $path;
        } else {
            unset($data['image']); // 🚀 prevents overwriting with null
        }

        Legislation::create($data);

        return redirect()->route('admin.legislations.index')->with('success', 'Legislation created successfully.');
    }

    public function edit(Legislation $legislation)
    {
        return Inertia::render('Admin/Legislation/Edit', [
            'legislation' => $legislation,
        ]);
    }

    public function update(Request $request, Legislation $legislation)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string|max:255',
            'details' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'link' => 'nullable|url',
            'is_visible' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('legislation', 'public');
            $data['image'] = $path;
        } else {
            unset($data['image']); // 🚀 prevents overwriting with null
        }

        $legislation->update($data);

        return redirect()->route('admin.legislations.index')->with('success', 'Legislation updated successfully.');
    }

    public function destroy(Legislation $legislation)
    {
        $legislation->delete();

        return redirect()->route('admin.legislations.index')->with('success', 'Legislation deleted successfully.');
    }
}
