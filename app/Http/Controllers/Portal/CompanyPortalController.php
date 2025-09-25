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
        $companyInfo = CompanyInfo::first();
        $employees = Employee::all()->map(function ($employee) {
            return [
                'id' => $employee->id,
                'name' => $employee->name,
                'position' => $employee->position,
                'department' => $employee->department,
                'is_visible' => $employee->is_visible,
                'photo' => $employee->photo_url, // send URL to frontend
            ];
        });
        return Inertia::render('Portal/CompanyInfo', [
            'companyInfo' => [
                'background' => $companyInfo?->background,
                'vision' => $companyInfo?->vision,
                'mission' => $companyInfo?->mission,
                'is_visible' => $companyInfo?->is_visible,
            ],
            'employees' => $employees,
        ]);
    }


    public function aboutUsOrganization()
    {
        $companyInfo = CompanyInfo::first();
        $employees = Employee::where('is_visible', true)->get();
        // $aboutUsContents = AboutUsContent::where('section', 'organization')->get();
        return Inertia::render('Portal/AboutUsOrganization', [
            'companyInfo' => $companyInfo,
            'employees' => $employees,
            // 'aboutUsContents' => $aboutUsContents,
        ]);
    }

    public function aboutUsStandards()
    {
        $standards = StandardApplication::all()->map(function ($standard) {
            $standard->logo = $standard->logo
                ? asset('storage/' . $standard->logo) // if using storage/app/public
                : null;
            return $standard;
        });

        return Inertia::render('Portal/AboutUsStandards', [
            'aboutUsStandards' => $standards,
        ]);
    }


    public function aboutUsLegislation()
    {
        $legislations = Legislation::all()->map(function ($legislation) {
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
        $services = Service::with('images')->get()->map(function ($service) {
            $service->images = $service->images->map(function ($image) {
                $image->url = url('storage/' . $image->url); // Ensure full URL is generated
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
        $services = Service::with('images')->get()->map(function ($service) {
            $service->images = $service->images->map(function ($image) {
                $image->url = url('storage/' . $image->url); // Ensure full URL is generated
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
        $services = Service::with('images')->get()->map(function ($service) {
            $service->images = $service->images->map(function ($image) {
                $image->url = url('storage/' . $image->url); // Ensure full URL is generated
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
        $services = Service::with('images')->get()->map(function ($service) {
            $service->images = $service->images->map(function ($image) {
                $image->url = url('storage/' . $image->url); // Ensure full URL is generated
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
        $contactInfo = ContactInfo::all();
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
