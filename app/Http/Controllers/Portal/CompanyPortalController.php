<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\CompanyInfo;
use App\Models\AboutUsContent;
use App\Models\Service;
use App\Models\ContactInfo;
use App\Models\HomeSection;
use App\Models\Employee;
use App\Models\Position;
use App\Models\CompetentPerson;
use App\Models\StandardApplication;
use App\Models\Legislation;
use App\Models\Customer;
use App\Mail\CustomerMessageMail;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Illuminate\Http\Request;

class CompanyPortalController extends Controller
{
    public function home()
    {
        return Inertia::render('Portal/Home', [
            'companyInfo' => CompanyInfo::all([
                'background',
                'vision',
                'mission',
                'is_visible',
            ]),
            'services' => Service::all([
                'type',
                'title',
                'summary',
                'details',
                'image',
                'is_visible',
            ]),
            'homeSections' => HomeSection::all([
                'title',
                'top_details',
                'top_image',
                'bottom_details',
                'bottom_image',
            ]),
        ]);
    }
    public function show()
    {
        $companyInfo = CompanyInfo::first();
        // $aboutUs = AboutUsContent::all();
        $services = Service::all();
        $contactInfo = ContactInfo::first();
        $homeSections = HomeSection::all();

        return Inertia::render('Portal/Home', [
            'companyInfo' => $companyInfo,
            // 'aboutUs' => $aboutUs,
            'services' => $services,
            'contactInfo' => $contactInfo,
            'homeSections' => $homeSections,
        ]);
    }

    public function companyInfo()
    {
        // 1. Fetch Company Info
        $companyInfo = CompanyInfo::where('is_visible', true)->first();

        // 2. Fetch Positions and Eager-Load Visible Employees (N+1 fix)
        // We constrain the eager-loaded 'employees' relationship to only include visible ones.
        $rawPositions = Position::where('is_visible', true)
            ->with([
                'employees' => function ($query) {
                    $query->where('is_visible', true);
                },
                // load additional parents via pivot so frontend can render matrix connectors
                'parentsPivot'
            ])
            ->orderBy('rank')
            ->get();

        $competentPersons = CompetentPerson::with('employee')->where('is_active', true)->get()->map(function ($person) {
            return [
                'id' => $person->id,
                'name' => $person->name,
                'dosh_numbers' => $person->dosh_numbers,
                'competencies' => $person->competencies,
                'bio' => $person->bio,
                'is_active' => $person->is_active,
                'employee' => $person->employee ? [
                    'id' => $person->employee->id,
                    'name' => $person->employee->name,
                    'photo' => $person->employee->photo_url,
                ] : null,
            ];
        });

        // 3. Map positions to arrays and attach the *already loaded* visible employees
        $positionsById = [];
        foreach ($rawPositions as $position) {
            // Employees are already loaded here due to the 'with' clause (no new query)
            $emps = $position->employees->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'photo' => $employee->photo_url,
                    'department' => $employee->department,
                ];
            })->toArray();

            // build parent_ids (primary first, then additional parents from pivot)
            $parentIds = [];
            if ($position->parent_id) $parentIds[] = $position->parent_id;
            if ($position->relationLoaded('parentsPivot')) {
                $additional = $position->parentsPivot->pluck('id')->toArray();
                foreach ($additional as $ap) {
                    if (!in_array($ap, $parentIds)) $parentIds[] = $ap;
                }
            }

            $positionsById[$position->id] = [
                'id' => $position->id,
                'name' => $position->name,
                'parent_id' => $position->parent_id,
                'parent_ids' => $parentIds,
                'rank' => $position->rank,
                'is_visible' => $position->is_visible,
                'employees' => $emps,
                'children' => [],
            ];
        }

        // 4. Build nested tree structure
        $positionsTree = [];
        foreach ($positionsById as $id => &$pos) {
            if ($pos['parent_id'] && isset($positionsById[$pos['parent_id']])) {
                // Attach as a child to its parent
                $positionsById[$pos['parent_id']]['children'][] = &$pos;
            } else {
                // This is a root node (no parent or missing parent)
                $positionsTree[] = &$pos;
            }
        }
        unset($pos); // Break the reference to avoid unexpected modifications

        // 5. Ensure children are ordered by rank recursively
        $sortChildren = function (&$nodes) use (&$sortChildren) {
            usort($nodes, function ($a, $b) {
                // Null-safe comparison for rank
                return ($a['rank'] ?? 0) <=> ($b['rank'] ?? 0);
            });
            foreach ($nodes as &$n) {
                if (!empty($n['children'])) {
                    $sortChildren($n['children']);
                }
            }
        };
        $sortChildren($positionsTree);

        // 6. Employees without a position (fallback)
        $unassignedEmployees = Employee::whereNull('position_id')
            ->where('is_visible', true)
            ->get()
            ->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'photo' => $employee->photo_url,
                    'department' => $employee->department,
                ];
            });

        // 7. Render with Inertia
        return Inertia::render('Portal/CompanyInfo', [
            'companyInfo' => [
                'background' => $companyInfo?->background,
                'vision' => $companyInfo?->vision,
                'mission' => $companyInfo?->mission,
                'is_visible' => $companyInfo?->is_visible,
            ],
            'positions' => $positionsTree,
            'unassignedEmployees' => $unassignedEmployees,
            'competentPersons' => $competentPersons,
        ]);
    }

    // public function aboutUsOrganization()
    // {
    //     $companyInfo = CompanyInfo::where('is_visible', true)->first();
    //     $employees = Employee::where('is_visible', true)->get();

    //     return Inertia::render('Portal/AboutUsOrganization', [
    //         'companyInfo' => $companyInfo,
    //         'employees' => $employees,
    //     ]);
    // }

    public function aboutUsStandards()
    {
        $standards = StandardApplication::where('is_visible', true)->get()->map(function ($standard) {
            $standard->logo = $standard->logo
                ? asset('storage/' . $standard->logo)
                : null;
            return $standard;
        });

        return Inertia::render('Portal/AboutUsStandards', [
            'aboutUsStandards' => $standards,
        ]);
    }

    public function aboutUsLegislation()
    {
        $legislations = Legislation::where('is_visible', true)->get()->map(function ($legislation) {
            $legislation->image = $legislation->image
                ? asset('storage/' . $legislation->image)
                : null;
            return $legislation;
        });

        return Inertia::render('Portal/AboutUsLegislation', [
            'aboutUsLegislations' => $legislations,
        ]);
    }

    public function servicesHealthSafety()
    {
        $services = Service::where('is_visible', true)
            ->with('images')
            ->get()
            ->map(function ($service) {
                $service->images = $service->images->map(function ($image) {
                    $image->url = url('storage/' . $image->url);
                    return $image;
                });
                return $service;
            });

        return Inertia::render('Portal/ServicesHealthSafety', [
            'services' => $services,
        ]);
    }

    public function servicesTraining()
    {
        $services = Service::where('is_visible', true)
            ->with('images')
            ->get()
            ->map(function ($service) {
                $service->images = $service->images->map(function ($image) {
                    $image->url = url('storage/' . $image->url);
                    return $image;
                });
                return $service;
            });

        return Inertia::render('Portal/ServicesTraining', [
            'services' => $services,
        ]);
    }

    public function servicesEngineering()
    {
        $services = Service::where('is_visible', true)
            ->with('images')
            ->get()
            ->map(function ($service) {
                $service->images = $service->images->map(function ($image) {
                    $image->url = url('storage/' . $image->url);
                    return $image;
                });
                return $service;
            });

        return Inertia::render('Portal/ServicesEngineering', [
            'services' => $services,
        ]);
    }

    public function servicesEnvironmental()
    {
        $services = Service::where('is_visible', true)
            ->with('images')
            ->get()
            ->map(function ($service) {
                $service->images = $service->images->map(function ($image) {
                    $image->url = url('storage/' . $image->url);
                    return $image;
                });
                return $service;
            });

        return Inertia::render('Portal/ServicesEnvironmental', [
            'services' => $services,
        ]);
    }

    public function contact()
    {
        $contactInfo = ContactInfo::where('is_visible', true)->get();
        return Inertia::render('Portal/Contact', [
            'contactInfo' => $contactInfo,
        ]);
    }

    public function submitCustomerForm(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Customer::create($validatedData);

        // Send email to the address from .env
        Mail::to(env('MAIL_FROM_ADDRESS'))->send(new CustomerMessageMail($validatedData));

        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }
}
