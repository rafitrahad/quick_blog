<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class userController extends Controller
{

    public function index()
    {
        return view('index');
    }

    public function signupView()
    {
        return view('signup');
    }

    // Process user registration
    public function signup(Request $request)
    {
        // 1. Validate only the user inputs
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // 2. Create user (all other fields are automatically populated)
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // 3. Log the user in
        Auth::login($user);

        // 4. Redirect
        return redirect()->route('index')->with('status', 'Registration successful!');
    }


    public function loginView()
    {
        return view('login');
    }

    // 2. Handle Login Submission
    public function login(Request $request)
    {
        // Validate inputs
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Check if "Remember Me" was checked
        $remember = $request->boolean('remember');

        // Attempt to log the user in
        if (Auth::attempt($credentials, $remember)) {
            // Regenerate session ID to prevent session fixation attacks
            $request->session()->regenerate();

            return redirect()->intended(route('index'))
                ->with('status', 'Welcome back!');
        }

        // If credentials fail, redirect back with error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // 3. Handle Logout
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate current session and regenerate CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('loginView')->with('status', 'You have been logged out.');
    }

    public function forgotPasswordView()
    {
        return view('forgotPassword');
    }

    public function adminDashboard()
    {
        return view('adminDashboard');
    }

    
}
