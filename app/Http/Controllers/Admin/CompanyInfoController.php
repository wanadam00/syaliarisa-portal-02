<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyInfo;
use Faker\Provider\ar_EG\Company;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompanyInfoController extends Controller
{
    public function index()
    {
        $companyInfo = CompanyInfo::all();
        return Inertia::render('Admin/CompanyInfo/Index', [
            'companyInfo' => $companyInfo,
        ]);
    }

    public function create(CompanyInfo $companyInfo)
    {
        return Inertia::render('Admin/CompanyInfo/Create', [
            'companyInfo' => $companyInfo,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'background' => 'required|string',
            'vision' => 'required|string',
            'mission' => 'required|string',
            'is_visible' => 'boolean',
        ]);
        CompanyInfo::create($data);
        return redirect()->route('admin.company-info.index')->with('success', 'Company info created successfully.');
    }

    public function edit(CompanyInfo $companyInfo)
    {
        // $companyInfo = CompanyInfo::first();
        return Inertia::render('Admin/CompanyInfo/Edit', [
            'companyInfo' => $companyInfo,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'background' => 'required|string',
            'vision' => 'required|string',
            'mission' => 'required|string',
            'is_visible' => 'boolean',
        ]);
        $companyInfo = CompanyInfo::first();
        if ($companyInfo) {
            $companyInfo->update($data);
        } else {
            CompanyInfo::create($data);
        }
        return redirect()->route('admin.company-info.index')->with('success', 'Company info updated successfully.');
    }

    public function destroy(CompanyInfo $companyInfo)
    {
        $companyInfo->delete();
        return redirect()->route('admin.company-info.index')->with('success', 'Company info deleted successfully.');
    }
}
