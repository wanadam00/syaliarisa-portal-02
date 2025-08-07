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

    public function create()
    {
        return Inertia::render('Admin/ContactInfo/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|string',
            'map_embed' => 'nullable|string',
            'is_visible' => 'boolean',
        ]);
        ContactInfo::create($data);
        return redirect()->route('admin.contact-info.index')->with('success', 'Contact info created successfully.');
    }

    public function edit()
    {
        $contactInfo = ContactInfo::first();
        return Inertia::render('Admin/ContactInfo/Edit', [
            'contactInfo' => $contactInfo,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|string',
            'map_embed' => 'nullable|string',
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
}
