<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class userController extends Controller
{
    public function home()
    {
        return view('index');
    }

    public function register(Request $request)
    {
        // dd("useradded"); // Dump and die to inspect the request data
        //validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);



        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        $request->session()->regenerate();


        return redirect(route('home'));
    }

    public function loginView(Request $request)
    {
        // dd("login"); // Dump and die to inspect the request data

        return view('login');
    }

    public function login(Request $request)
    {
        // dd("login"); // Dump and die to inspect the request data

        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect(route('home'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('home'));
    }

    public function signup(Request $request)
    {
        // dd("signup"); // Dump and die to inspect the request data

        return view('signup');
    }
}
