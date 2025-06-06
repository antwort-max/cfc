<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\PrdProduct;
use App\View\Components\TopBanner;
use Illuminate\Http\Request;
use App\Models\WebThemeOption;
use App\Models\WebMenu;
use App\Helpers\ActivityLogger; 

class ProductSearchController extends Controller
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

    public function index(Request $request)
    {
        $banner = [];
        $q = $request->input('q');

        $products = PrdProduct::with(['family', 'subfamily'])
            ->where(function($query) use ($q) {
                $query->where('sku', 'like', "%{$q}%")
                      ->orWhere('name', 'like', "%{$q}%")
                      ->orWhere('description', 'like', "%{$q}%")
                      ->orWhereHas('family', function($qf) use ($q) {
                          $qf->where('name', 'like', "%{$q}%");
                      })
                      ->orWhereHas('subfamily', function($qs) use ($q) {
                          $qs->where('name', 'like', "%{$q}%");
                      });
            })
            ->paginate(20)->withQueryString();
        
        $categories = $products->pluck('category')->filter()->unique('id')->values();
        $families = $products->pluck('family')->filter()->unique('id')->values();                
        $brands = $products->pluck('brand')->filter()->unique('id')->values();

        ActivityLogger::log($request, 'search_products', [
            'query'         => $q,
            'result_count'  => $products->total(),
        ]);

        return view('ecommerce.products.list', compact('banner', 'products', 'families', 'brands', 'categories'));
    }
}
