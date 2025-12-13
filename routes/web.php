<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeSectionController;
use App\Http\Controllers\Admin\CompanyInfoController;
use App\Http\Controllers\Admin\AboutUsContentController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ContactInfoController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\LegislationController;
use App\Http\Controllers\Admin\StandardApplicationController;
use App\Http\Controllers\Portal\CompanyPortalController;
use App\Http\Controllers\Auth\PasswordController;
use App\Models\ContactInfo;

use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Welcome');
// })->name('home');

// Route::get('dashboard', function () {
//     return Inertia::render('admin/home');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::prefix('/')->group(function () {
Route::get('/', [CompanyPortalController::class, 'home'])->name('portal.home');
Route::get('/about-us/company-info', [CompanyPortalController::class, 'companyInfo'])->name('portal.company-info');
Route::get('/about-us/organization', [CompanyPortalController::class, 'aboutUsOrganization'])->name('portal.about-us.organization');
Route::get('/about-us/standards', [CompanyPortalController::class, 'aboutUsStandards'])->name('portal.about-us.standards');
Route::get('/about-us/legislation', [CompanyPortalController::class, 'aboutUsLegislation'])->name('portal.about-us.legislation');
Route::get('/services/safety-health', [CompanyPortalController::class, 'servicesHealthSafety'])->name('portal.services.health-safety');
Route::get('/services/training', [CompanyPortalController::class, 'servicesTraining'])->name('portal.services.training');
Route::get('/services/engineering', [CompanyPortalController::class, 'servicesEngineering'])->name('portal.services.engineering');
Route::get('/services/environmental', [CompanyPortalController::class, 'servicesEnvironmental'])->name('portal.services.environmental');
Route::get('/contact-us', [CompanyPortalController::class, 'contact'])->name('portal.contact');
Route::post('/customers', [CompanyPortalController::class, 'submitCustomerForm'])->name('portal.customers.submit');
// });

// Password update routes
// Password update routes
Route::middleware('auth')->group(function () {
    Route::get('/password/update', [PasswordController::class, 'edit'])
        ->name('password.forceUpdate'); // use unique name

    Route::post('/password/update', [PasswordController::class, 'update'])
        ->name('password.forceUpdate.store'); // use POST
});


Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    // Home Sections
    Route::get('/home', [HomeSectionController::class, 'index'])->name('admin.home-sections.index');
    Route::get('/home/create', [HomeSectionController::class, 'create'])->name('admin.home-sections.create');
    Route::post('/home', [HomeSectionController::class, 'store'])->name('admin.home-sections.store');
    Route::get('/home/{homeSection}/edit', [HomeSectionController::class, 'edit'])->name('admin.home-sections.edit');
    Route::post('/home/{homeSection}', [HomeSectionController::class, 'update'])->name('admin.home-sections.update');
    Route::delete('/home/{homeSection}', [HomeSectionController::class, 'destroy'])->name('admin.home-sections.destroy');
    // Company Info
    Route::get('/company-info', [CompanyInfoController::class, 'index'])->name('admin.company-info.index');
    Route::get('/company-info/create', [CompanyInfoController::class, 'create'])->name('admin.company-info.create');
    Route::post('/company-info', [CompanyInfoController::class, 'store'])->name('admin.company-info.store');
    Route::get('/company-info/{companyInfo}/edit', [CompanyInfoController::class, 'edit'])->name('admin.company-info.edit');
    Route::post('/company-info/{companyInfo}', [CompanyInfoController::class, 'update'])->name('admin.company-info.update');
    Route::delete('/company-info/{companyInfo}', [CompanyInfoController::class, 'destroy'])->name('admin.company-info.destroy');
    // Services
    Route::get('/services', [ServiceController::class, 'index'])->name('admin.services.index');
    Route::get('/services/create', [ServiceController::class, 'create'])->name('admin.services.create');
    Route::post('/services', [ServiceController::class, 'store'])->name('admin.services.store');
    Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('admin.services.edit');
    Route::post('/services/{service}', [ServiceController::class, 'update'])->name('admin.services.update');
    Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('admin.services.destroy');
    // Employees
    Route::get('/employees', [EmployeeController::class, 'index'])->name('admin.employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('admin.employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('admin.employees.store');
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('admin.employees.edit');
    Route::post('/employees/{employee}', [EmployeeController::class, 'update'])->name('admin.employees.update');
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('admin.employees.destroy');
    // Positions
    Route::get('/positions', [PositionController::class, 'index'])->name('admin.positions.index');
    Route::get('/positions/create', [PositionController::class, 'create'])->name('admin.positions.create');
    Route::post('/positions', [PositionController::class, 'store'])->name('admin.positions.store');
    Route::post('/positions/ajax', [PositionController::class, 'storeAjax'])->name('admin.positions.storeAjax');
    Route::get('/positions/{position}/edit', [PositionController::class, 'edit'])->name('admin.positions.edit');
    Route::post('/positions/{position}', [PositionController::class, 'update'])->name('admin.positions.update');
    Route::delete('/positions/{position}', [PositionController::class, 'destroy'])->name('admin.positions.destroy');
    // Contact Info
    Route::get('/contact', [ContactInfoController::class, 'index'])->name('admin.contact-info.index');
    Route::get('/contact/create', [ContactInfoController::class, 'create'])->name('admin.contact-info.create');
    Route::post('/contact', [ContactInfoController::class, 'store'])->name('admin.contact-info.store');
    Route::get('/contact/{contactInfo}/edit', [ContactInfoController::class, 'edit'])->name('admin.contact-info.edit');
    Route::post('/contact/{contactInfo}', [ContactInfoController::class, 'update'])->name('admin.contact-info.update');
    Route::delete('/contact/{contactInfo}', [ContactInfoController::class, 'destroy'])->name('admin.contact-info.destroy');
    // Legislations
    Route::get('/legislations', [LegislationController::class, 'index'])->name('admin.legislations.index');
    Route::get('/legislations/create', [LegislationController::class, 'create'])->name('admin.legislations.create');
    Route::post('/legislations', [LegislationController::class, 'store'])->name('admin.legislations.store');
    Route::get('/legislations/{legislation}/edit', [LegislationController::class, 'edit'])->name('admin.legislations.edit');
    Route::post('/legislations/{legislation}', [LegislationController::class, 'update'])->name('admin.legislations.update');
    Route::delete('/legislations/{legislation}', [LegislationController::class, 'destroy'])->name('admin.legislations.destroy');

    // Standard Applications
    Route::get('/standard-applications', [StandardApplicationController::class, 'index'])->name('admin.standard-applications.index');
    Route::get('/standard-applications/create', [StandardApplicationController::class, 'create'])->name('admin.standard-applications.create');
    Route::post('/standard-applications', [StandardApplicationController::class, 'store'])->name('admin.standard-applications.store');
    Route::get('/standard-applications/{standardApplication}/edit', [StandardApplicationController::class, 'edit'])->name('admin.standard-applications.edit');
    Route::post('/standard-applications/{standardApplication}', [StandardApplicationController::class, 'update'])->name('admin.standard-applications.update');
    Route::delete('/standard-applications/{standardApplication}', [StandardApplicationController::class, 'destroy'])->name('admin.standard-applications.destroy');
});

// API route to fetch all contact info
Route::get('/api/contact-info', function () {
    $contactInfo = ContactInfo::first();

    return response()->json([
        'contact_info' => $contactInfo ?? [
            'address' => '',
            'phone' => '',
            'email' => '',
            'business_hours' => '',
        ]
    ]);
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
// #2262ae
// #3da948
// #48b2e5
