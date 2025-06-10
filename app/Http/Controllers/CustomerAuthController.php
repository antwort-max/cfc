<?php

namespace App\Http\Controllers;

use App\Models\CusCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Ecommerce\Services\CartService;

class CustomerAuthController extends Controller
{
    public function showRegister()
    {
        return view('ecommerce.auth.register');
    }

    public function showLogin(Request $req)
    {
        if ($req->has('redirect')) {
            $req->session()->put('url.intended', $req->query('redirect'));
        }

        return view('ecommerce.auth.login');
    }

    public function register(Request $req, CartService $cartService)
    {
        $req->validate([
            'first_name' => 'required|string|max:60',
            'last_name'  => 'required|string|max:60',
            'email'      => 'required|email|unique:cus_customers,email',
            'password'   => 'required|min:8|confirmed',
        ]);

        $customer = CusCustomer::create([
            'first_name' => $req->first_name,
            'last_name'  => $req->last_name,
            'email'      => $req->email,
            'password'   => Hash::make($req->password),
            'status'     => true,
        ]);

        Auth::guard('customer')->login($customer);

        // Asigna el carrito de invitado al nuevo cliente
        $cartService->assignGuestCartToCustomer($customer->id);

        return redirect()->route('customer.dashboard');
    }

    public function login(Request $req, CartService $cartService)
    {
        $cred = $req->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('customer')->attempt($cred, $req->boolean('remember'))) {
            $customer = $req->user('customer');

            $customer->forceFill(['last_login_at' => now()])->saveQuietly();
            $req->session()->regenerate();

            // Asigna el carrito de invitado al cliente
            $cartService->assignGuestCartToCustomer($customer->id);

            return redirect()->intended(route('cart.show'));
        }

        return back()->withErrors([
            'email' => 'Las credenciales no son válidas.',
        ])->onlyInput('email');
    }

    public function logout(Request $req)
    {
        Auth::guard('customer')->logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();

        return redirect()->route('customer.login');
    }
}
