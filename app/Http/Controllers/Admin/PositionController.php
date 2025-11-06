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
        // Load primary parent and additional parents (pivot) to show all reporting lines
        $positions = Position::with(['parent', 'parentsPivot'])->orderBy('rank', 'asc')->paginate(10);

        // Attach a reports_to array to each item (primary first, then additional)
        $positions->getCollection()->transform(function ($p) {
            $reports = [];
            if ($p->parent) {
                $reports[] = ['id' => $p->parent->id, 'name' => $p->parent->name, 'type' => 'primary'];
            }
            foreach ($p->parentsPivot as $pp) {
                if (!$p->parent || $pp->id !== $p->parent->id) {
                    $reports[] = ['id' => $pp->id, 'name' => $pp->name, 'type' => 'additional'];
                }
            }
            // Attach to model for serialization to Inertia
            $p->reports_to = $reports;
            return $p;
        });

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
            'parent_ids' => 'nullable|array',
            'parent_ids.*' => 'nullable|distinct|exists:positions,id',
            'rank' => 'nullable|integer',
            'description' => 'nullable|string',
            'is_visible' => 'boolean',
        ]);

        // Determine primary parent (first selected) if any
        $parentIds = $data['parent_ids'] ?? [];
        $primaryParent = count($parentIds) ? $parentIds[0] : null;

        $position = Position::create([
            'name' => $data['name'],
            'parent_id' => $primaryParent,
            'rank' => $data['rank'] ?? 0,
            'description' => $data['description'] ?? null,
            'is_visible' => $request->boolean('is_visible'),
        ]);

        // Sync any additional parents via pivot table (prevent cycles)
        // Sync pivot with all selected parents excluding the primary (to avoid duplication)
        $additional = array_values(array_filter($parentIds, fn($id) => $id !== $primaryParent));
        $valid = [];
        foreach ($additional as $pid) {
            if (!$this->wouldCreateCycle($position->id, $pid)) {
                $valid[] = $pid;
            }
        }
        $position->parentsPivot()->sync($valid);

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

        // Build parent_ids array where primary parent (parent_id) is first, followed by any additional parents
        // pluck the positions.id explicitly to avoid ambiguity with pivot.id column
        $additional = $position->parentsPivot()->pluck('positions.id')->toArray();
        $parentIds = [];
        if ($position->parent_id) {
            $parentIds[] = $position->parent_id;
        }
        foreach ($additional as $pid) {
            if ($pid !== $position->parent_id) {
                $parentIds[] = $pid;
            }
        }

        return Inertia::render('Admin/Position/Edit', [
            'position' => $position,
            'positions' => $positions,
            'parent_ids' => $parentIds,
        ]);
    }

    public function update(Request $request, Position $position)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'parent_ids' => 'nullable|array',
            'parent_ids.*' => 'nullable|distinct|exists:positions,id',
            'rank' => 'nullable|integer',
            'description' => 'nullable|string',
            'is_visible' => 'boolean',
        ]);

        // Determine primary parent (first selected)
        $parentIds = $data['parent_ids'] ?? [];
        $primaryParent = count($parentIds) ? $parentIds[0] : null;

        $position->update([
            'name' => $data['name'],
            'parent_id' => $primaryParent,
            'rank' => $data['rank'] ?? 0,
            'description' => $data['description'] ?? null,
            'is_visible' => $request->boolean('is_visible'),
        ]);

        // Sync additional parents (exclude primary)
        $additional = array_values(array_filter($parentIds, fn($id) => $id !== $primaryParent));
        $valid = [];
        foreach ($additional as $pid) {
            if (!$this->wouldCreateCycle($position->id, $pid)) {
                $valid[] = $pid;
            }
        }
        $position->parentsPivot()->sync($valid);

        return redirect()->route('admin.positions.index')->with('success', 'Position updated.');
    }

    public function destroy(Position $position)
    {
        // On delete, children will set parent_id to null because of migration constraint
        $position->delete();
        return redirect()->route('admin.positions.index')->with('success', 'Position deleted.');
    }

    /**
     * Detect if assigning $candidateParentId as a parent of $childId would create a cycle.
     * We traverse parents (primary parent via parent_id and additional parents via pivot) upwards
     * and see if we reach $childId.
     */
    private function wouldCreateCycle(int $childId, int $candidateParentId): bool
    {
        // Quick rejects
        if ($childId === $candidateParentId) {
            return true;
        }

        // BFS up the parent graph starting from candidateParentId
        $visited = [];
        $stack = [$candidateParentId];

        while (!empty($stack)) {
            $nodeId = array_pop($stack);
            if ($nodeId === $childId) {
                return true;
            }
            if (in_array($nodeId, $visited, true)) {
                continue;
            }
            $visited[] = $nodeId;

            $node = Position::with(['parentsPivot'])->find($nodeId);
            if (!$node) {
                continue;
            }

            // Primary parent via parent_id
            if ($node->parent_id) {
                $stack[] = $node->parent_id;
            }

            // Additional parents via pivot
            foreach ($node->parentsPivot as $p) {
                $stack[] = $p->id;
            }
        }

        return false;
    }
}
