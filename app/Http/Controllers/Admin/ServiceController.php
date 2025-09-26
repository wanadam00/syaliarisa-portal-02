<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceImage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index()
    {
        // load services with images
        $services = Service::with('images')->get()->map(function ($service) {
            $service->images->transform(function ($img) {
                $img->url = asset('storage/' . $img->url);
                return $img;
            });
            return $service;
        });

        return Inertia::render('Admin/Service/Index', [
            'services' => $services,
        ]);
    }

    public function create(Service $service)
    {
        return Inertia::render('Admin/Service/Create', [
            'service' => $service,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'details' => 'nullable|string',
            'link' => 'nullable|string|max:500', // ✅ allow video link
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_visible' => 'boolean',
            'deleted_images' => 'array',
            'deleted_images.*' => 'integer|exists:service_images,id',
        ]);

        // Create service first
        $service = Service::create([
            'type' => $data['type'],
            'title' => $data['title'],
            'summary' => $data['summary'],
            'details' => $data['details'],
            'link' => $data['link'] ?? null, // ✅ save video link
            'is_visible' => $request->boolean('is_visible'),
        ]);

        // 🆕 Delete selected images
        if (!empty($data['deleted_images'])) {
            $images = ServiceImage::whereIn('id', $data['deleted_images'])->get();
            foreach ($images as $img) {
                Storage::disk('public')->delete($img->url);
                $img->delete();
            }
        }

        // Save images to service_images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('services', 'public');
                $service->images()->create(['url' => $path]);
            }
        }

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        $service->load('images');

        $service->images->transform(function ($img) {
            $img->url = asset('storage/' . $img->url);
            return $img;
        });

        // Make sure the video link (if uploaded) is full URL
        if ($service->link && !str_starts_with($service->link, 'http')) {
            $service->link = asset('storage/' . $service->link);
        }

        return Inertia::render('Admin/Service/Edit', [
            'service' => $service,
        ]);
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'details' => 'nullable|string',
            'link' => 'nullable|string|max:500', // ✅ allow video link
            'is_visible' => 'boolean',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'deleted_images' => 'array',
            'deleted_images.*' => 'integer',
        ]);

        // update service data
        $service->update([
            'type' => $validated['type'],
            'title' => $validated['title'],
            'summary' => $validated['summary'],
            'details' => $validated['details'],
            'link' => $validated['link'] ?? null, // ✅ save video link
            'is_visible' => $validated['is_visible'] ?? false,
        ]);

        // handle deleted images
        if (!empty($validated['deleted_images'])) {
            $service->images()->whereIn('id', $validated['deleted_images'])->delete();
        }

        // handle new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('services', 'public');
                $service->images()->create(['url' => $path]);
            }
        }

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }
}
