<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Validation\Rules; // kama hutumii Rules::Password unaweza kuacha comment

class RegisteredUserController extends Controller
{
    /**
     * Show the registration view.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],

            // Phone as main login identifier: must start with 255 + 9 digits (total 12 digits)
            'phone' => [
                'required',
                'regex:/^255[0-9]{9}$/',
                'unique:users,phone',
            ],

            // Email is optional now
            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:users,email',
            ],

            // Simple password rule (unaweza kubadilisha baadaye ukataka)
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'phone'    => $request->phone,
            'email'    => $request->email, // optional, inaweza kuwa null
            'password' => Hash::make($request->password),
        ]);

        // Kama unataka waliojisajili wote default wawe "parent", unaweza ku-uncomment hii line:
        // $user->assignRole('parent');

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
