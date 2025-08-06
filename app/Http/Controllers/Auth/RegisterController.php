<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Tampilkan form registrasi.
     */
    public function showForm()
    {
        if (Auth::check()) {
            return redirect('/panel');
        }

        return view('auth.register');
    }

    /**
     * Proses form registrasi.
     */
    public function submitForm(Request $request)
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role'         => ['required', 'in:buyer,seller'],
            'password'     => ['required', 'confirmed', 'min:6'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address'      => ['nullable', 'string', 'max:500'],
        ]);

        $user = User::create([
            'name'         => $validated['name'],
            'email'        => $validated['email'],
            'role'         => $validated['role'],
            'password'     => Hash::make($validated['password']),
            'phone_number' => $validated['phone_number'] ?? null,
            'address'      => $validated['address'] ?? null,
        ]);

        Auth::login($user);

        return redirect('/panel');
    }
}
