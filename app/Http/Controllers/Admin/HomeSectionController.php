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
        $sections = HomeSection::all();
        return Inertia::render('Admin/HomeSection/Index', [
            'sections' => $sections,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/HomeSection/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'top_details' => 'required|string|max:255',
            'top_image' => 'nullable|string|max:255',
            'bottom_details' => 'nullable|string|max:255',
            'bottom_image' => 'nullable|string|max:255',
            'is_visible' => 'boolean',
        ]);
        HomeSection::create($data);
        return redirect()->route('admin.home-sections.index')->with('success', 'Section created successfully.');
    }

    public function edit(HomeSection $homeSection)
    {
        return Inertia::render('Admin/HomeSection/Edit', [
            'section' => $homeSection,
        ]);
    }

    public function update(Request $request, HomeSection $homeSection)
    {
        $data = $request->validate([
            'top_details' => 'required|string|max:255',
            'top_image' => 'nullable|string|max:255',
            'bottom_details' => 'nullable|string|max:255',
            'bottom_image' => 'nullable|string|max:255',
            'is_visible' => 'boolean',
        ]);
        $homeSection->update($data);
        return redirect()->route('admin.home-sections.index')->with('success', 'Section updated successfully.');
    }

    public function destroy(HomeSection $homeSection)
    {
        $homeSection->delete();
        return redirect()->route('admin.home-sections.index')->with('success', 'Section deleted successfully.');
    }
}
