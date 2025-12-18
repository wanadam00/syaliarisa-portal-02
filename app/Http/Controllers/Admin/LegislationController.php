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
        // Paginate legislations and include image URL
        $legislations = Legislation::orderBy('created_at', 'desc')
            ->paginate(10)
            ->through(function ($legislation) {
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
        // provide existing types for dropdown + add-new option
        $types = Legislation::query()->pluck('type')->unique()->values()->filter()->values();
        return Inertia::render('Admin/Legislation/Create', [
            'legislation' => $legislation,
            'types' => $types,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'nullable|string|max:255',
            'details' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (strip_tags($value) === '') {
                    $fail('The ' . $attribute . ' field is required.');
                }
            }],
            'title' => 'required|string|max:255',
            'description' => ['required', 'string', function ($attribute, $value, $fail) {
                if (strip_tags($value) === '') {
                    $fail('The ' . $attribute . ' field is required.');
                }
            }],
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'link' => 'nullable|url',
            'is_visible' => 'boolean',
            'display_mode' => 'in:group,individual',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('legislation', 'public');
            $data['image'] = $path;
        } else {
            unset($data['image']); // 🚀 prevents overwriting with null
        }

        // Default display mode
        if (!isset($data['display_mode'])) {
            $data['display_mode'] = 'group';
        }

        Legislation::create($data);

        return redirect()->route('admin.legislations.index')->with('success', 'Legislation created successfully.');
    }

    public function edit(Legislation $legislation)
    {
        // provide existing types for dropdown and ensure current type is available
        $types = Legislation::query()->pluck('type')->unique()->values()->filter()->values();
        if ($legislation->type && !$types->contains($legislation->type)) {
            $types->push($legislation->type);
        }
        return Inertia::render('Admin/Legislation/Edit', [
            'legislation' => $legislation,
            'types' => $types,
        ]);
    }

    public function update(Request $request, Legislation $legislation)
    {
        $data = $request->validate([
            'type' => 'nullable|string|max:255',
            'details' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (strip_tags($value) === '') {
                    $fail('The ' . $attribute . ' field is required.');
                }
            }],
            'title' => 'required|string|max:255',
            'description' => ['required', 'string', function ($attribute, $value, $fail) {
                if (strip_tags($value) === '') {
                    $fail('The ' . $attribute . ' field is required.');
                }
            }],
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'link' => 'nullable|url',
            'is_visible' => 'boolean',
            'display_mode' => 'in:group,individual',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('legislation', 'public');
            $data['image'] = $path;
        } else {
            unset($data['image']); // 🚀 prevents overwriting with null
        }

        // Default display mode
        if (!isset($data['display_mode'])) {
            $data['display_mode'] = 'group';
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
