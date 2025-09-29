<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PasswordController extends Controller
{
    /**
     * Show update password page.
     */
    public function edit()
    {
        return Inertia::render('auth/UpdatePassword');
    }

    /**
     * Handle password update.
     */
    public function update(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = $request->user();
        $user->password = Hash::make($request->password);
        $user->email_verified_at = now(); // ✅ mark verified
        $user->save();

        return redirect()->intended(route('admin.home-sections.index'))
            ->with('status', 'Password updated successfully!');
    }
}
