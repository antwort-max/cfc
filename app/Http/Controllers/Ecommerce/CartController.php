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

class CartController extends Controller
{
    protected $themeOptions;
    protected $menus;

    public function __construct()
    {
        $this->themeOptions = WebThemeOption::query()->where('status', true)->latest('id')->first();
        view()->share('themeOptions', $this->themeOptions);

        $this->menus = WebMenu::query()->with('children')->where('status', true)->whereNull('parent_id')->orderBy('order')->get();
        view()->share('menus', $this->menus);
    }

    public function index(Request $request, CartService $cart)
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

        return back()->with('success', 'Producto añadido al carrito');
    }

    public function show(CartService $cart, Request $request)
    {
        $currentCart = $cart->current();

        ActivityLogger::log($request, 'view_cart', [
            'item_count' => $cart->current()->items()->count(),
        ]);

        return view('ecommerce.cartPage.index', ['cart' => $currentCart->load('items.product'),]);
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

    public function selectCustomer(Request $request)
    {
        return view('ecommerce.cartPage.selectCustomer', ['clients' => Client::all(), ]);
    }

    public function abandoned(Request $request)
    {
        $customer = $request->user('customer');
        $carts = EcoCart::where('user_id', $customer->id)
            ->where('status', 'pending')
            ->get();

        return view('ecommerce.customer.abandonedCarts', compact('carts'));
    }

    public function restore(Request $request, EcoCart $cart)
    {
        $current = $this->cart->current(); 
        $current->items()->delete();

        foreach ($cart->items as $item) {
            $current->items()->create([
                'product_id'    => $item->product_id,
                'quantity'      => $item->quantity,
                'package_price' => $item->package_price,
                // …otros campos que use tu EcoCartItem
            ]);
        }

        ActivityLogger::log($request, 'restore_abandoned_cart', [
            'restored_cart_id' => $cart->id,
            'items_count' => $cart->items()->count(),
        ]);

        return redirect()->route('cart.show')->with('success', 'Tu carro abandonado ha sido restaurado.');
    }

}
