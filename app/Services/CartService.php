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

    public function assignGuestCartToCustomer(int $customerId): void
    {
        if ($token = Session::get('guest_token')) {
            EcoCart::where('guest_token', $token)
                ->update([
                    'customer_id' => $customerId,
                    'guest_token' => null,
                    'status'      => EcoCart::STATUS_PENDING,
                ]);

            Session::forget('guest_token');
        }
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
        // 1) Si está logueado como cliente...
        if (Auth::guard('customer')->check()) {
            $customer = Auth::guard('customer')->user();

            // Busca o crea un carrito open para este customer_id
            return EcoCart::firstOrCreate(
                [
                    'customer_id' => $customer->id,
                    'status'      => EcoCart::STATUS_OPEN,
                ],
                [
                    // campos adicionales en el CREATE, p.ej. IP
                    'ip_address' => request()->ip(),
                ]
            );
        }

        // 2) Si NO está logueado, manejo de invitado:
        // — Genero o recupero un guest_token de sesión
        $session = session();
        if (! $session->has('guest_token')) {
            $session->put('guest_token', Str::uuid()->toString());
        }
        $token = $session->get('guest_token');

        // Busca o crea un carrito open para este guest_token
        return EcoCart::firstOrCreate(
            [
                'guest_token' => $token,
                'status'      => EcoCart::STATUS_OPEN,
            ],
            [
                'ip_address' => request()->ip(),
            ]
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
