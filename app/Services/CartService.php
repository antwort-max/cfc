<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\{EcoCart, EcoCartItem, PrdProduct};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class CartService
{
    protected float $taxRate; 

    public function __construct()
    {
        $this->taxRate = config('cart.tax_rate');
    }

    protected function getToken(): string
    {
        if (Auth::check()) {
            return 'user_'.$this->guarded()->id;    // opcional: enlazar por usuario
        }
        // ó usa guest_token en sesión (o cookie) como antes:
        if (!session()->has('guest_token')) {
            session(['guest_token' => Str::uuid()->toString()]);
        }
        return session('guest_token');
    }

    public function current(): EcoCart
    {
        if ($user = Auth::guard('customer')->user()) {
            return EcoCart::firstOrCreate(
                ['customer_id' => $user->id, 'status' => 'open'],
                ['ip_address'  => request()->ip()]
            );
        }

        // Invitado: usa guest_token de sesión
        if (! Session::has('guest_token')) {
            Session::put('guest_token', Str::uuid()->toString());
        }
        $token = Session::get('guest_token');

        return EcoCart::firstOrCreate(
            ['guest_token' => $token, 'status' => 'open'],
            ['ip_address'   => request()->ip()]
        );
    }

    public function addProduct(int $productId, int $quantity = 1): EcoCart
    {
        return DB::transaction(function () use ($productId, $quantity) {

            $product = PrdProduct::findOrFail($productId);
            $cart    = $this->current();

            // ② Busca si el ítem ya existe
            $item = $cart->items()
                ->where('product_id', $productId)
                ->first();

            if ($item) {
                $item->increment('quantity', $quantity);
            } else {
                $cart->items()->create([
                    'product_id'     => $product->id,
                    'sku'            => $product->sku,
                    'name'           => $product->name,
                    'package_unit'   => $product->package_unit,
                    'package_qty'    => $product->package_qty,
                    'package_price'  => $product->package_price,
                    'quantity'       => $quantity,
                ]);
            }

            $cart->amount = $cart->items
                ->sum(fn ($i) => $i->package_price * $i->quantity);

            $cart->taxes  = round($cart->amount * config('shop.tax_rate', 0.19), 0);
            $cart->save();

            return $cart->refresh();
        });
    }
    /**
     * Calcula el subtotal del carrito (sin impuestos).
     */
    public function getTotal(): float
    {
        $cart = $this->current();
        return $cart->items->reduce(function ($sum, $item) {
            return $sum + ($item->product->price * $item->quantity);
        }, 0.0);
    }

    /**
     * Calcula el monto de impuestos del carrito.
     */
    public function getTaxes(): float
    {
        $subtotal = $this->getTotal();
        return round($subtotal * $this->taxRate, 2);
    }
}
