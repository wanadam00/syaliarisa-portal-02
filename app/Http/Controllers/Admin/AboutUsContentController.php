<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutUsContent;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AboutUsContentController extends Controller
{
    public function index()
    {
        $contents = AboutUsContent::orderBy('section')->orderBy('title')->get();
        return Inertia::render('Admin/AboutUsContent/Index', [
            'contents' => $contents,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/AboutUsContent/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'section' => 'required|in:organization,standards_application,legislation',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_visible' => 'boolean',
        ]);
        AboutUsContent::create($data);
        return redirect()->route('admin.about-us-contents.index')->with('success', 'Content created successfully.');
    }

    public function edit(AboutUsContent $aboutUsContent)
    {
        return Inertia::render('Admin/AboutUsContent/Edit', [
            'content' => $aboutUsContent,
        ]);
    }

    public function update(Request $request, AboutUsContent $aboutUsContent)
    {
        $data = $request->validate([
            'section' => 'required|in:organization,standards_application,legislation',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_visible' => 'boolean',
        ]);
        $aboutUsContent->update($data);
        return redirect()->route('admin.about-us-contents.index')->with('success', 'Content updated successfully.');
    }

    public function destroy(AboutUsContent $aboutUsContent)
    {
        $aboutUsContent->delete();
        return redirect()->route('admin.about-us-contents.index')->with('success', 'Content deleted successfully.');
    }
}
