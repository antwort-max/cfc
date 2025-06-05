<?php 

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\PrdProduct;
use App\Models\WebThemeOption;
use App\Models\WebMenu;

class ProductShowController extends Controller
{
    protected $themeOptions;
    protected $menus;

    public function __construct()
    {

        $this->themeOptions = WebThemeOption::query()
            ->where('status', true)
            ->latest('id')
            ->first();

        view()->share('themeOptions', $this->themeOptions);

        $this->menus = WebMenu::query()
            ->with('children')           
            ->where('status', true)
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();
        
        view()->share('menus', $this->menus);
    }

    public function show(PrdProduct $product)
    {
        return view('ecommerce.products.show', compact('product'));
    }
}
