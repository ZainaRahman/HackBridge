<?php
// FILE: app/Http/Controllers/Auth/RegisteredUserController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('welcome'); // show landing page (modal opens via JS)
    }

    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name'  => ['required', 'string', 'max:50'],
            'email'      => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'department' => ['required', 'string'],
            'year'       => ['required', 'integer', 'min:1', 'max:5'],
            'password'   => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        // The "sign up as admin" dropdown is only ever honored in local/dev.
        // In any other environment the field is ignored entirely, even if submitted.
        if (app()->environment('local')) {
            $rules['role'] = ['nullable', 'in:user,admin'];
        }

        $request->validate($rules);

        $user = User::create([
            'name'       => $request->first_name . ' ' . $request->last_name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'department' => $request->department,
            'university' => $request->university ?? 'KUET',
            'year'       => $request->year,
            'availability' => 'open',
        ]);

        if (app()->environment('local') && $request->input('role') === 'admin') {
            $user->is_admin = true;
            $user->save();
        }

        event(new Registered($user));
        $user->markEmailAsVerified();
        Auth::login($user);

        return redirect()->route('dashboard');
    }
}