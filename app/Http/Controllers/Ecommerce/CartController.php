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

        return back()->with('success', 'Producto añadido al carrito');
    }

    public function show(CartService $cart)
    {
        $currentCart = $cart->current();
        return view('ecommerce.cartPage.index', ['cart' => $currentCart->load('items.product'),]);
    }

    public function update(EcoCartItem $item, Request $request): RedirectResponse
    {
        $request->validate(['quantity' => 'required|integer|min:1',]);
        $item->update(['quantity' => $request->integer('quantity'),]);

        return back()->with('success', 'Cantidad actualizada');
    }

    public function destroy(EcoCartItem $item): RedirectResponse
    {
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
        // 1) Vaciar el carrito actual (opcional)
        $current = $this->cart->current(); 
        $current->items()->delete();

        // 2) Copiar items del carro abandonado al carrito actual
        foreach ($cart->items as $item) {
            $current->items()->create([
                'product_id'    => $item->product_id,
                'quantity'      => $item->quantity,
                'package_price' => $item->package_price,
                // …otros campos que use tu EcoCartItem
            ]);
        }

        // 3) Opcional: borrar el carro abandonado o marcarlo como “restaurado”
        // $cart->delete();

        return redirect()
            ->route('cart.show')
            ->with('success', 'Tu carro abandonado ha sido restaurado.');
    }

}
