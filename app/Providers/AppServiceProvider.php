<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Models\ContactInfo;
use App\Models\Position;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Inertia::share([
            'contactInfo' => fn() => ContactInfo::first(), // assuming single record
            'positionsTree' => function () {
                try {
                    $positions = Position::with(['employees', 'parentsPivot'])->orderBy('rank')->get();

                    // Build map
                    $map = [];
                    foreach ($positions as $p) {
                        $map[$p->id] = [
                            'id' => $p->id,
                            'name' => $p->name,
                            'parent_id' => $p->parent_id,
                            'rank' => $p->rank,
                            'employees' => $p->employees->map(fn($e) => ['id' => $e->id, 'name' => $e->name])->values()->all(),
                            // secondary parents (additional) for annotations
                            'secondary_parents' => $p->parentsPivot->map(fn($pp) => ['id' => $pp->id, 'name' => $pp->name])->values()->all(),
                            'children' => [],
                        ];
                    }

                    // Attach children by primary parent_id
                    $tree = [];
                    foreach ($map as $id => &$node) {
                        if ($node['parent_id'] && isset($map[$node['parent_id']])) {
                            $map[$node['parent_id']]['children'][] = &$node;
                        } else {
                            $tree[] = &$node;
                        }
                    }

                    // Filter to only nodes that have employees (directly or in descendants)
                    $hasEmployees = null;
                    $hasEmployees = function ($node) use (&$hasEmployees) {
                        if (!empty($node['employees'])) {
                            return true;
                        }
                        foreach ($node['children'] as $child) {
                            if ($hasEmployees($child)) {
                                return true;
                            }
                        }
                        return false;
                    };

                    $filterTree = function ($nodes) use (&$filterTree, $hasEmployees) {
                        $out = [];
                        foreach ($nodes as $n) {
                            if ($hasEmployees($n)) {
                                $copy = $n;
                                $copy['children'] = $filterTree($n['children']);
                                $out[] = $copy;
                            }
                        }
                        return $out;
                    };

                    return $filterTree($tree);
                } catch (\Throwable $e) {
                    return [];
                }
            },
        ]);

        // If APP_URL is an HTTPS URL (for example when using ngrok), force
        // generated URLs to use HTTPS. This is a safe fallback for local
        // development and prevents mixed-content issues when TLS is
        // terminated by a proxy (ngrok) and forwarded to the app.
        if (str_starts_with(config('app.url', ''), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
