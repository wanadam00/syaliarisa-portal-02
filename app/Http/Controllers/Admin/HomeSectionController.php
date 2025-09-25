<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSection;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeSectionController extends Controller
{
    public function index()
    {
        $sections = HomeSection::all()->map(function ($section) {
            $section->top_image = $section->top_image
                ? asset('storage/' . $section->top_image)
                : null;
            $section->bottom_image = $section->bottom_image
                ? asset('storage/' . $section->bottom_image)
                : null;
            return $section;
        });
        return Inertia::render('Admin/HomeSection/Index', [
            'sections' => $sections,
        ]);
    }

    public function create(HomeSection $homeSection)
    {
        return Inertia::render('Admin/HomeSection/Create', [
            'homeSection' => $homeSection,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'top_details' => 'required|string|max:255',
            'top_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'bottom_details' => 'required|string|max:255',
            'bottom_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_visible' => 'nullable|boolean',
        ]);

        // Handle top_image upload
        if ($request->hasFile('top_image')) {
            $path = $request->file('top_image')->store('topImage', 'public');
            $data['top_image'] = $path;
        } else {
            unset($data['top_image']); // 🚀 prevents overwriting with null
        }

        // Handle bottom_image upload
        if ($request->hasFile('bottom_image')) {
            $path = $request->file('bottom_image')->store('bottomImage', 'public');
            $data['bottom_image'] = $path;
        } else {
            unset($data['bottom_image']); // 🚀 prevents overwriting with null
        }
        HomeSection::create($data);
        return redirect()->route('admin.home-sections.index')->with('success', 'Section created successfully.');
    }

    public function edit(HomeSection $homeSection)
    {
        return Inertia::render('Admin/HomeSection/Edit', [
            'homeSection' => $homeSection,
        ]);
    }

    public function update(Request $request, HomeSection $homeSection)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'top_details' => 'required|string|max:255',
            'top_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'bottom_details' => 'required|string|max:255',
            'bottom_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_visible' => 'nullable|boolean',
        ]);

        // Handle top_image upload
        if ($request->hasFile('top_image')) {
            $path = $request->file('top_image')->store('topImage', 'public');
            $data['top_image'] = $path;
        } else {
            unset($data['top_image']); // 🚀 prevents overwriting with null
        }

        // Handle bottom_image upload
        if ($request->hasFile('bottom_image')) {
            $path = $request->file('bottom_image')->store('bottomImage', 'public');
            $data['bottom_image'] = $path;
        } else {
            unset($data['bottom_image']); // 🚀 prevents overwriting with null
        }
        $homeSection->update($data);
        return redirect()->route('admin.home-sections.index')->with('success', 'Section updated successfully.');
    }

    public function destroy(HomeSection $homeSection)
    {
        $homeSection->delete();
        return redirect()->route('admin.home-sections.index')->with('success', 'Section deleted successfully.');
    }
}
