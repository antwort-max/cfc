<?php

namespace App\Http\Controllers;

use App\Models\CusCustomer;
use App\Models\EcoCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    /* ---------- Formularios ---------- */
    public function showRegister()
    {
        return view('ecommerce.auth.register');
    }

    public function showLogin()
    {
        return view('ecommerce.auth.login');
    }

    /* ---------- Registro ---------- */
    public function register(Request $req)
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

        // Loguea al cliente recién registrado
        Auth::guard('customer')->login($customer);

        // —— Persistencia del carrito ——  
        if ($token = $req->session()->get('guest_token')) {
            EcoCart::where('guest_token', $token)
                   ->update(['user_id' => $customer->id]);
        }
        // —— fin persistencia ——  

        return redirect()->route('customer.dashboard');
    }

    /* ---------- Login ---------- */
    public function login(Request $req)
    {
        $cred = $req->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('customer')->attempt($cred, $req->boolean('remember'))) {

            // última conexión
            $req->user('customer')
                ->forceFill(['last_login_at' => now()])
                ->saveQuietly();

            $req->session()->regenerate();

            // —— Persistencia del carrito ——  
            if ($token = $req->session()->get('guest_token')) {
                EcoCart::where('guest_token', $token)
                       ->update(['user_id' => $req->user('customer')->id]);
            }
            // —— fin persistencia ——  

            return redirect()->intended(route('customer.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Las credenciales no son válidas.',
        ])->onlyInput('email');
    }

    /* ---------- Logout ---------- */
    public function logout(Request $req)
    {
        Auth::guard('customer')->logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();

        return redirect()->route('customer.login');
    }
}
