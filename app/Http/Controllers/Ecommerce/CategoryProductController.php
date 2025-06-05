<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\PrdProduct;
use App\Models\PrdBrand;
use App\Models\PrdCategory;
use Illuminate\Http\Request;
use App\Models\WebThemeOption;
use App\Models\WebMenu;

class CategoryProductController extends Controller
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

    public function show(PrdCategory $category)
    {
        $banner = $category;

        $products = PrdProduct::with(['family', 'subfamily', 'brand', 'category'])
            ->where('category_id', $category->id)
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

        $brands = $products
            ->pluck('brand')
            ->filter()
            ->unique('id')
            ->values();
            
        return view('ecommerce.products.list', compact('banner', 'products', 'families', 'brands', 'categories'))->with('currentBrand', $category);;
    }


}