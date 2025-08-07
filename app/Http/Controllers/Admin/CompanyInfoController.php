<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyInfo;
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

    public function create()
    {
        return Inertia::render('Admin/CompanyInfo/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'background' => 'nullable|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'is_visible' => 'boolean',
        ]);
        CompanyInfo::create($data);
        return redirect()->route('admin.company-info.index')->with('success', 'Company info created successfully.');
    }

    public function edit()
    {
        $companyInfo = CompanyInfo::first();
        return Inertia::render('Admin/CompanyInfo/Edit', [
            'companyInfo' => $companyInfo,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'background' => 'nullable|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
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
}
