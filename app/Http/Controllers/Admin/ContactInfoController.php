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
            'address'        => 'required|string',
            'phone'          => ['required', 'regex:/^\+60\d{8,10}$/'],   // ✅ must start with +60
            'mobile_phone_1' => ['required', 'regex:/^\+60\d{8,10}$/'],
            'mobile_phone_2' => ['nullable', 'regex:/^\+60\d{8,10}$/'],
            'email'          => 'required|email',
            'business_hours' => 'required|string',
            'is_visible'     => 'boolean',
        ], [
            'phone.regex' => 'The phone number must be in the format +60XXXXXXXXX',
            'mobile_phone_1.regex' => 'Mobile phone 1 must be in the format +60XXXXXXXXX',
            'mobile_phone_2.regex' => 'Mobile phone 2 must be in the format +60XXXXXXXXX',
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
            'address'        => 'required|string',
            'phone'          => ['required', 'regex:/^\+60\d{8,10}$/'],   // ✅ must start with +60
            'mobile_phone_1' => ['required', 'regex:/^\+60\d{8,10}$/'],
            'mobile_phone_2' => ['nullable', 'regex:/^\+60\d{8,10}$/'],
            'email'          => 'required|email',
            'business_hours' => 'required|string',
            'is_visible'     => 'boolean',
        ], [
            'phone.regex' => 'The phone number must be in the format +60XXXXXXXXX',
            'mobile_phone_1.regex' => 'Mobile phone 1 must be in the format +60XXXXXXXXX',
            'mobile_phone_2.regex' => 'Mobile phone 2 must be in the format +60XXXXXXXXX',
        ]);

        $contactInfo = ContactInfo::first();

        if ($contactInfo) {
            $contactInfo->update($data);
        } else {
            ContactInfo::create($data);
        }

        return redirect()->route('admin.contact-info.index')
            ->with('success', 'Contact info updated successfully.');
    }

    public function destroy(ContactInfo $contactInfo)
    {
        $contactInfo->delete();
        return redirect()->route('admin.contact-info.index')->with('success', 'Contact info deleted successfully.');
    }
}
