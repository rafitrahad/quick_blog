<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display the user dashboard / profile settings page.
     */
    public function dashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $commentsCount = $user->comments()->count();
        $postsCount = $user->posts()->count();

        return view('userDashboard', compact('user', 'commentsCount', 'postsCount'));
    }

    /**
     * Update user profile information (name, email).
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);

        $user->update($validated);

        return back()->with('status', 'Profile information updated successfully!');
    }

    /**
     * Change user password.
     */
    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'The provided current password does not match your records.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('status', 'Password changed successfully!');
    }

    /**
     * Deactivate the user account (Soft Delete).
     */
    public function deactivate(Request $request)
    {
        $request->validate([
            'confirm_password' => ['required', 'string'],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($request->confirm_password, $user->password)) {
            return back()->withErrors([
                'confirm_password' => 'Incorrect password. Please enter your valid current password to confirm account deactivation.',
            ]);
        }

        // Perform soft delete (account is retained in DB, but inactive)
        $user->delete();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'Your account has been deactivated successfully. We are sorry to see you go.');
    }
}
