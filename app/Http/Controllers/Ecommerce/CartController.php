<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Models\EcoCartItem;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use App\Models\WebThemeOption;
use App\Models\WebMenu;
use App\Models\EcoCart;
use App\Helpers\ActivityLogger;  


class CartController extends BaseEcommerceController
{

    public function show(CartService $cart, Request $request)
    {
        if (! $request->session()->has('guest_token')) {
            $request->session()->put('guest_token', Str::uuid()->toString());
        }

        $currentCart = $cart->current();

        ActivityLogger::log($request, 'view_cart', [
            'item_count' => $cart->current()->items()->count(),
        ]);

        return view('ecommerce.cartPage.index', ['cart' => $currentCart->load('items.product'),]);
    }

    public function store(Request $request, CartService $cart): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:prd_products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $cart->addProduct(
            productId: $request->integer('product_id'),
            quantity : $request->integer('quantity')
        );

        ActivityLogger::log($request, 'add_to_cart', [
            'product_id' => $request->integer('product_id'),
            'quantity' => $request->integer('quantity'),
        ]);

        return redirect()->route('cart.show')->with('success', 'Producto añadido al carrito');
    }

   

    public function update(EcoCartItem $item, Request $request): RedirectResponse
    {
        $request->validate(['quantity' => 'required|integer|min:1',]);
        $item->update(['quantity' => $request->integer('quantity'),]);

        ActivityLogger::log($request, 'update_cart_item', [
            'product_id'    => $item->product_id,
            'new_quantity'  => $request->integer('quantity'),
        ]);
        
        return back()->with('success', 'Cantidad actualizada');
    }

    public function destroy(EcoCartItem $item, Request $request): RedirectResponse
    {
        ActivityLogger::log($request, 'remove_cart_item', [
            'product_id' => $item->product_id,
        ]);

        $item->delete();
        return back()->with('success', 'Producto eliminado del carrito');
    }


    public function restore(Request $request, EcoCart $abandonedCart, CartService $cartService)
    {
        $currentCart = $cartService->current();
        $currentCart->items()->delete();

        foreach ($abandonedCart->items as $item) {
            $currentCart->items()->create([
                'product_id'    => $item->product_id,
                'quantity'      => $item->quantity,
                'package_price' => $item->package_price,
            ]);
        }

        ActivityLogger::log($request, 'restore_abandoned_cart', [
            'restored_cart_id' => $abandonedCart->id,
            'items_count'      => $abandonedCart->items()->count(),
        ]);

        return redirect()
            ->route('cart.show')
            ->with('success', 'Tu carro abandonado ha sido restaurado.');
    }
    
    public function assignCustomer(Request $request, CartService $cartService)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:cus_customers,id',
        ]);

        $currentCart = $cartService->current();

        $currentCart->update([
            'customer_id' => $data['customer_id'],
            'guest_token' => null,
            'status'      => EcoCart::STATUS_PENDING,
        ]);

        $request->session()->forget('guest_token');

        return redirect()->route('cart.show')
                        ->with('success', 'Carrito vinculado al cliente.');
    }

    public function showAbandoned(Request $request)
    {
        $customer = $request->user('customer');

        // Busca todos los carritos que no han sido convertidos en orden
        $carts = EcoCart::where('customer_id', $customer->id)
                        ->whereIn('status', [
                            EcoCart::STATUS_OPEN,
                            EcoCart::STATUS_ABANDONED,
                            EcoCart::STATUS_CONVERTED, // Si quisieras incluir convertidos, sino quítalo
                        ])
                        ->where('id', '!=', session('current_cart_id')) // opcional: excluye el actual
                        ->get();

        return view('ecommerce.customer.abandonedCarts', compact('carts'));
    }


}
