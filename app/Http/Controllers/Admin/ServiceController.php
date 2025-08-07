<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('type')->orderBy('title')->get();
        return Inertia::render('Admin/Service/Index', [
            'services' => $services,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Service/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:health_safety,training,engineering,environmental',
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'details' => 'required|string',
            'image' => 'nullable|string',
            'is_visible' => 'boolean',
        ]);
        Service::create($data);
        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        return Inertia::render('Admin/Service/Edit', [
            'service' => $service,
        ]);
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'type' => 'required|in:health_safety,training,engineering,environmental',
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'details' => 'required|string',
            'image' => 'nullable|string',
            'is_visible' => 'boolean',
        ]);
        $service->update($data);
        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }
}
