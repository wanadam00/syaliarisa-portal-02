<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::with('parent')->orderBy('rank', 'asc')->paginate(10);
        return Inertia::render('Admin/Position/Index', [
            'positions' => $positions,
        ]);
    }

    public function create()
    {
        $positions = Position::orderBy('rank', 'asc')->get();
        return Inertia::render('Admin/Position/Create', [
            'positions' => $positions,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:positions,id',
            'rank' => 'nullable|integer',
            'description' => 'nullable|string',
            'is_visible' => 'boolean',
        ]);

        $position = Position::create([
            'name' => $data['name'],
            'parent_id' => $data['parent_id'] ?? null,
            'rank' => $data['rank'] ?? 0,
            'description' => $data['description'] ?? null,
            'is_visible' => $request->boolean('is_visible'),
        ]);

        return redirect()->route('admin.positions.index')->with('success', 'Position created.');
    }

    // AJAX quick-create used by inline forms
    public function storeAjax(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:positions,id',
            'rank' => 'nullable|integer',
        ]);

        // Normalize name: trim and collapse internal whitespace
        $name = trim(preg_replace('/\s+/', ' ', $data['name']));

        // Try to find an existing position with same name (case-insensitive) and same parent
        $existing = Position::whereRaw('LOWER(name) = ?', [strtolower($name)])
            ->where('parent_id', $data['parent_id'] ?? null)
            ->first();

        if ($existing) {
            // return existing to make inline-creation idempotent
            return response()->json($existing->load('parent'), 200);
        }

        $position = Position::create([
            'name' => $name,
            'parent_id' => $data['parent_id'] ?? null,
            'rank' => $data['rank'] ?? 0,
            'is_visible' => true,
        ]);

        return response()->json($position->load('parent'), 201);
    }

    public function edit(Position $position)
    {
        $positions = Position::where('id', '!=', $position->id)->orderBy('rank', 'asc')->get();
        return Inertia::render('Admin/Position/Edit', [
            'position' => $position,
            'positions' => $positions,
        ]);
    }

    public function update(Request $request, Position $position)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:positions,id',
            'rank' => 'nullable|integer',
            'description' => 'nullable|string',
            'is_visible' => 'boolean',
        ]);

        $position->update([
            'name' => $data['name'],
            'parent_id' => $data['parent_id'] ?? null,
            'rank' => $data['rank'] ?? 0,
            'description' => $data['description'] ?? null,
            'is_visible' => $request->boolean('is_visible'),
        ]);

        return redirect()->route('admin.positions.index')->with('success', 'Position updated.');
    }

    public function destroy(Position $position)
    {
        // On delete, children will set parent_id to null because of migration constraint
        $position->delete();
        return redirect()->route('admin.positions.index')->with('success', 'Position deleted.');
    }
}
