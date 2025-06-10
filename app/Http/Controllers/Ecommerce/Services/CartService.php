<?php

namespace App\Http\Controllers\Ecommerce\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\EcoCart;
use App\Models\EcoCartItem;
use App\Models\PrdProduct;

class CartService
{
    protected float $taxRate;

    public function __construct()
    {
        $this->taxRate = config('cart.tax_rate', 0);
    }

    /**
     * Obtiene o genera el token de carrito (usuario o invitado).
     */
    protected function getToken(): string
    {
        if (Auth::guard('customer')->check()) {
            return 'user_'.Auth::guard('customer')->id();
        }
        if (! Session::has('guest_token')) {
            Session::put('guest_token', Str::uuid()->toString());
        }
        return Session::get('guest_token');
    }

    /**
     * Retorna el carrito "actual" (auth o guest), guardando su ID en sesión.
     */
    public function current(): EcoCart
    {
        // Cliente autenticado
        if (Auth::guard('customer')->check()) {
            $customer = Auth::guard('customer')->user();
            if ($id = Session::get('current_cart_id')) {
                return EcoCart::findOrFail($id);
            }
            $cart = EcoCart::firstOrCreate(
                ['customer_id' => $customer->id, 'status' => EcoCart::STATUS_OPEN],
                ['ip_address' => request()->ip()]
            );
            Session::put('current_cart_id', $cart->id);
            return $cart;
        }

        // Invitado
        $token = $this->getToken();
        $cart = EcoCart::firstOrCreate(
            ['guest_token' => $token, 'status' => EcoCart::STATUS_OPEN],
            ['ip_address' => request()->ip()]
        );
        Session::put('current_cart_id', $cart->id);
        return $cart;
    }

    /**
     * Fusiona carritos de invitado en uno del cliente al loguearse.
     */
    public function assignGuestCartToCustomer(int $customerId): void
    {
        $token = Session::get('guest_token');
        if (! $token) {
            return;
        }

        $guestCarts = EcoCart::where('guest_token', $token)
            ->where('status', EcoCart::STATUS_OPEN)
            ->get();

        foreach ($guestCarts as $guestCart) {
            // Cerrar previos OPEN del cliente
            EcoCart::where('customer_id', $customerId)
                ->where('status', EcoCart::STATUS_OPEN)
                ->update(['status' => EcoCart::STATUS_ABANDONED]);

            $customerCart = EcoCart::firstOrCreate(
                ['customer_id' => $customerId, 'status' => EcoCart::STATUS_OPEN],
                ['ip_address' => $guestCart->ip_address]
            );

            // Migrar items
            foreach ($guestCart->items as $item) {
                $exist = $customerCart->items()
                    ->where('product_id', $item->product_id)
                    ->first();
                if ($exist) {
                    $exist->increment('quantity', $item->quantity);
                } else {
                    $customerCart->items()->create($item->toArray());
                }
            }

            $this->updateCartTotals($customerCart);

            $guestCart->update([
                'status'      => EcoCart::STATUS_MERGED,
                'merged_into' => $customerCart->id,
            ]);

            Session::forget('guest_token');
            Session::put('current_cart_id', $customerCart->id);
        }
    }

    /**
     * Añade un producto al carrito en transacción.
     */
    public function addProduct(int $productId, int $quantity = 1): EcoCart
    {
        return DB::transaction(function () use ($productId, $quantity) {
            $product = PrdProduct::findOrFail($productId);
            $cart    = $this->current();

            $item = $cart->items()->where('product_id', $productId)->first();
            if ($item) {
                $item->increment('quantity', $quantity);
            } else {
                $cart->items()->create([
                    'product_id'    => $product->id,
                    'sku'           => $product->sku,
                    'name'          => $product->name,
                    'package_unit'  => $product->package_unit,
                    'package_qty'   => $product->package_qty,
                    'package_price' => $product->package_price,
                    'quantity'      => $quantity,
                ]);
            }

            $this->updateCartTotals($cart);
            return $cart->refresh();
        });
    }

    /**
     * Recalcula y guarda montos neto e impuestos.
     */
    protected function updateCartTotals(EcoCart $cart): void
    {
        $netTotal = $cart->items->sum(fn($i) => $i->package_price * $i->quantity);
        $taxes    = round($netTotal * $this->taxRate, 2);

        $cart->update([
            'amount' => $netTotal,
            'taxes'  => $taxes,
        ]);
    }

    /**
     * Subtotal sin impuestos.
     */
    public function getTotal(): float
    {
        return $this->current()->items->sum(fn($i) => $i->package_price * $i->quantity);
    }

    /**
     * Total de impuestos.
     */
    public function getTaxes(): float
    {
        return round($this->getTotal() * $this->taxRate, 2);
    }
}
