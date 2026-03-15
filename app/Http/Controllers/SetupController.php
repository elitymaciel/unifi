<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class SetupController extends Controller
{
    /**
     * Show the first admin setup page.
     */
    public function index()
    {
        // Only allow if no users exist
        if (User::count() > 0) {
            return redirect()->route('login');
        }

        return view('auth.setup');
    }

    /**
     * Create the first administrator and log them in.
     */
    public function store(Request $request)
    {
        // Extra safety check
        if (User::count() > 0) {
            return redirect()->route('login');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        return redirect()->route('login')->with('success', 'Administrador criado com sucesso. Por favor, faça login.');
    }
}
