<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\CusCustomer;
use App\Models\EcoCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ActivityLogger; 

class RegisterController extends Controller
{

    public function fromGuest(Request $request)
    {

        $data = $request->validate([
            'email'                 => 'required|email|unique:cus_customers,email',
            'password'              => 'required|string|min:6|confirmed',
        ]);


        $customer = CusCustomer::create([
            'name'     => $request->input('name', 'Invitado'),
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);


        if ($token = $request->session()->get('guest_token')) {
            EcoCart::where('guest_token', $token)
                ->update([
                    'customer_id' => $customer->id,
                    'guest_token' => null,
                ]);
        }

        Auth::guard('customer')->login($customer);

        ActivityLogger::log($request, 'register_from_guest', [
            'customer_id' => $customer->id,
            'email'       => $customer->email,
            'linked_cart' => isset($token),
        ]);


        return redirect()->route('account.dashboard')->with('success', 'Tu cuenta ha sido creada y tus carritos vinculados.');
    }
}
