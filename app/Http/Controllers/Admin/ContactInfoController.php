<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInfo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactInfoController extends Controller
{
    public function index()
    {
        $contactInfo = ContactInfo::all();
        return Inertia::render('Admin/ContactInfo/Index', [
            'contactInfo' => $contactInfo,
        ]);
    }

    public function create(ContactInfo $contactInfo)
    {
        return Inertia::render('Admin/ContactInfo/Create', [
            'contactInfo' => $contactInfo,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'address' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|string',
            // 'map_embed' => 'nullable|string',
            'business_hours' => 'required|string',
            'is_visible' => 'boolean',
        ]);
        ContactInfo::create($data);
        return redirect()->route('admin.contact-info.index')->with('success', 'Contact info created successfully.');
    }

    public function edit(ContactInfo $contactInfo)
    {
        return Inertia::render('Admin/ContactInfo/Edit', [
            'contactInfo' => $contactInfo,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'address' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|string',
            // 'map_embed' => 'nullable|string',
            'business_hours' => 'required|string',
            'is_visible' => 'boolean',
        ]);
        $contactInfo = ContactInfo::first();
        if ($contactInfo) {
            $contactInfo->update($data);
        } else {
            ContactInfo::create($data);
        }
        return redirect()->route('admin.contact-info.index')->with('success', 'Contact info updated successfully.');
    }

    public function destroy(ContactInfo $contactInfo)
    {
        $contactInfo->delete();
        return redirect()->route('admin.contact-info.index')->with('success', 'Contact info deleted successfully.');
    }
}
