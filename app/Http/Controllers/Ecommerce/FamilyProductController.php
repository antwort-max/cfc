<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\PrdProduct;
use App\Models\PrdFamily;
use Illuminate\Http\Request;
use App\Models\WebThemeOption;
use App\Models\WebMenu;

class FamilyProductController extends Controller
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

   
    public function show(PrdFamily $family)
    {
     
        $banner = $family;

        // Obtiene productos de la familia
        $products = PrdProduct::with(['family', 'subfamily', 'brand'])
            ->where('family_id', $family->id)
            ->paginate(20)->withQueryString();

        $categories = $products
            ->pluck('category')
            ->filter()
            ->unique('id')
            ->values();

        $families = $products
            ->pluck('family')
            ->filter()
            ->unique('id')
            ->values();

        // Marcas únicas en estos productos
        $brands = $products
            ->pluck('brand')
            ->filter()
            ->unique('id')
            ->values();

        // Renderiza la lista reutilizando la vista de productos
        return view('ecommerce.products.list', compact(
            'categories',
            'banner',
            'products',
            'families',
            'brands',
        ))->with('currentFamily', $family);
    }
}



