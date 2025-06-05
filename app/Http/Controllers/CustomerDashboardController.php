<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EcoCart; // o el modelo de ventas que uses

class CustomerDashboardController extends Controller
{
    public function profile(Request $request)
    {
        $customer = $request->user('customer');
        return view('ecommerce.customer.profile', compact('customer'));
    }

    public function sales(Request $request)
    {
        $customer = $request->user('customer');
        $sales = EcoCart::where('customer_id', $customer->id)->get();
        return view('ecommerce.customer.sales', compact('sales'));
    }
}
