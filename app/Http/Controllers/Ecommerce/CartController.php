<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Ecommerce\BaseEcommerceController;
use App\Http\Controllers\Ecommerce\Services\CartService;
use App\Models\EcoCart;
use App\Models\EcoCartItem;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Helpers\ActivityLogger;

class CartController extends BaseEcommerceController
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        parent::__construct();  // comparte themeOptions y menus
        $this->cartService = $cartService;
      
    }

    /**
     * Muestra el carrito actual (invitado o cliente).
     */
    public function show(Request $request)
    {
        $cart = $this->cartService->current();

        ActivityLogger::log($request, 'view_cart', [
            'item_count' => $cart->items()->count(),
        ]);

        return view('ecommerce.cartPage.index', [
            'cart' => $cart->load('items.product'),
        ]);
    }

    /**
     * Añade un producto al carrito.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => 'required|exists:prd_products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $cart = $this->cartService->addProduct(
            productId: $data['product_id'],
            quantity:  $data['quantity'],
        );

        ActivityLogger::log($request, 'add_to_cart', [
            'product_id' => $data['product_id'],
            'quantity'   => $data['quantity'],
        ]);

        return redirect()
            ->route('cart.show')
            ->with('success', 'Producto añadido al carrito');
    }

    /**
     * Actualiza la cantidad de un ítem.
     */
    public function update(EcoCartItem $item, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item->update(['quantity' => $data['quantity']]);

        ActivityLogger::log($request, 'update_cart_item', [
            'product_id'   => $item->product_id,
            'new_quantity' => $data['quantity'],
        ]);

        return back()->with('success', 'Cantidad actualizada');
    }

    /**
     * Elimina un ítem del carrito.
     */
    public function destroy(EcoCartItem $item, Request $request): RedirectResponse
    {
        ActivityLogger::log($request, 'remove_cart_item', [
            'product_id' => $item->product_id,
        ]);

        $item->delete();

        return back()->with('success', 'Producto eliminado del carrito');
    }

    /**
     * Restaura un carrito abandonado.
     */
    public function restore(EcoCart $abandonedCart, Request $request): RedirectResponse
    {
        $userCart = $this->cartService->current();
        $userCart->items()->delete();

        foreach ($abandonedCart->items as $item) {
            $userCart->items()->create($item->toArray());
        }
        // Recalcular totales
        $this->cartService->addProduct(0, 0); // disparador de recálculo

        ActivityLogger::log($request, 'restore_abandoned_cart', [
            'restored_cart_id' => $abandonedCart->id,
            'items_count'      => $abandonedCart->items()->count(),
        ]);

        return redirect()
            ->route('cart.show')
            ->with('success', 'Tu carrito abandonado ha sido restaurado.');
    }

    /**
     * Asigna el carrito actual a un cliente manualmente.
     */
    public function selectCustomer(Request $request)
    {
        return view('ecommerce.cartPage.selectCustomer');
    }

    public function assignCustomer(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:cus_customers,id',
        ]);

        $cart = $this->cartService->current();
        $cart->update([   // solo cambiar customer_id, mantener status OPEN
            'customer_id' => $data['customer_id'],
            'guest_token' => null,
        ]);
        session()->forget('guest_token');
        session(['current_cart_id' => $cart->id]);

        return redirect()
            ->route('cart.show')
            ->with('success', 'Carrito vinculado al cliente.');
    }

    /**
     * Muestra carritos abandonados del cliente.
     */
    public function showAbandoned(Request $request)
    {
        $customerId = $request->user('customer')->id;
        $currentId  = session('current_cart_id');

        $carts = EcoCart::where('customer_id', $customerId)
            ->whereIn('status', [
                EcoCart::STATUS_ABANDONED,
                EcoCart::STATUS_OPEN,
                EcoCart::STATUS_CONVERTED,
            ])
            ->when($currentId, fn($q) => $q->where('id', '!=', $currentId))
            ->paginate(10)
            ->withQueryString();

        return view('ecommerce.customer.abandonedCarts', compact('carts'));
    }
}
