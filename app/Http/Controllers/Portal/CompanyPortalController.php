<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\CompanyInfo;
use App\Models\AboutUsContent;
use App\Models\Service;
use App\Models\ContactInfo;
use App\Models\HomeSection;
use App\Models\Employee;
use Inertia\Inertia;

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
            'aboutUsContents' => AboutUsContent::all([
                'section',
                'title',
                'content',
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
            'contactInfo' => ContactInfo::all([
                'address',
                'phone',
                'email',
                'map_embed',
                'is_visible',
            ]),
        ]);
    }
    public function show()
    {
        $companyInfo = CompanyInfo::first();
        $aboutUs = AboutUsContent::all();
        $services = Service::all();
        $contactInfo = ContactInfo::first();
        $homeSections = HomeSection::all();

        return Inertia::render('Portal/Home', [
            'companyInfo' => $companyInfo,
            'aboutUs' => $aboutUs,
            'services' => $services,
            'contactInfo' => $contactInfo,
            'homeSections' => $homeSections,
        ]);
    }

    public function companyInfo()
    {
        $companyInfo = CompanyInfo::first();
        $employees = Employee::all();
        return Inertia::render('Portal/CompanyInfo', [
            'companyInfo' => [
                'background' => $companyInfo->background,
                'vision' => $companyInfo->vision,
                'mission' => $companyInfo->mission,
                'is_visible' => $companyInfo->is_visible,
            ],
            'employees' => $employees,
        ]);
    }


    public function aboutUsOrganization()
    {
        $companyInfo = CompanyInfo::first();
        $employees = Employee::where('is_visible', true)->get();
        $aboutUsContents = AboutUsContent::where('section', 'organization')->get();
        return Inertia::render('Portal/AboutUsOrganization', [
            'companyInfo' => $companyInfo,
            'employees' => $employees,
            'aboutUsContents' => $aboutUsContents,
        ]);
    }

    public function aboutUsStandards()
    {
        $aboutUsContents = AboutUsContent::all();
        return Inertia::render('Portal/AboutUsStandards', [
            'aboutUsContents' => $aboutUsContents,
        ]);
    }

    public function aboutUsLegislation()
    {
        $aboutUsContents = AboutUsContent::all();
        return Inertia::render('Portal/AboutUsLegislation', [
            'aboutUsContents' => $aboutUsContents,
        ]);
    }

    public function servicesHealthSafety()
    {
        $services = Service::all();
        return Inertia::render('Portal/ServicesHealthSafety', [
            'services' => $services,
        ]);
    }

    public function servicesTraining()
    {
        $services = Service::all();
        return Inertia::render('Portal/ServicesTraining', [
            'services' => $services,
        ]);
    }

    public function servicesEngineering()
    {
        $services = Service::all();
        return Inertia::render('Portal/ServicesEngineering', [
            'services' => $services,
        ]);
    }

    public function servicesEnvironmental()
    {
        $services = Service::all();
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
}
