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
        $sections = HomeSection::orderBy('order')->get();
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
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'hero_image' => 'nullable|string',
            'is_visible' => 'boolean',
            'section_type' => 'required|string',
            'order' => 'integer',
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
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'hero_image' => 'nullable|string',
            'is_visible' => 'boolean',
            'section_type' => 'required|string',
            'order' => 'integer',
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
