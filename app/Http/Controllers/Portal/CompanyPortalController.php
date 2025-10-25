<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\CompanyInfo;
use App\Models\AboutUsContent;
use App\Models\Service;
use App\Models\ContactInfo;
use App\Models\HomeSection;
use App\Models\Employee;
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
        $companyInfo = CompanyInfo::where('is_visible', true)->first();
        // Eager-load position relation so we can use position id/name/rank on the portal
        // Build positions-first structure: positions ordered by rank, each with its visible employees
        $rawPositions = \App\Models\Position::where('is_visible', true)
            ->orderBy('rank')
            ->get();

        // Map positions to arrays and attach their visible employees
        $positionsById = [];
        foreach ($rawPositions as $position) {
            $emps = $position->employees()->where('is_visible', true)->get()->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'photo' => $employee->photo_url,
                    'department' => $employee->department,
                ];
            })->toArray();

            $positionsById[$position->id] = [
                'id' => $position->id,
                'name' => $position->name,
                'parent_id' => $position->parent_id,
                'rank' => $position->rank,
                'is_visible' => $position->is_visible,
                'employees' => $emps,
                'children' => [],
            ];
        }

        // Build nested tree
        $positionsTree = [];
        foreach ($positionsById as $id => &$pos) {
            if ($pos['parent_id'] && isset($positionsById[$pos['parent_id']])) {
                $positionsById[$pos['parent_id']]['children'][] = &$pos;
            } else {
                $positionsTree[] = &$pos;
            }
        }
        // Ensure children are ordered by rank
        $sortChildren = function (&$nodes) use (&$sortChildren) {
            usort($nodes, function ($a, $b) {
                return ($a['rank'] ?? 0) <=> ($b['rank'] ?? 0);
            });
            foreach ($nodes as &$n) {
                if (!empty($n['children'])) {
                    $sortChildren($n['children']);
                }
            }
        };
        $sortChildren($positionsTree);

        // Employees without a position (fallback)
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

        return Inertia::render('Portal/CompanyInfo', [
            'companyInfo' => [
                'background' => $companyInfo?->background,
                'vision' => $companyInfo?->vision,
                'mission' => $companyInfo?->mission,
                'is_visible' => $companyInfo?->is_visible,
            ],
            'positions' => $positionsTree,
            'unassignedEmployees' => $unassignedEmployees,
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
