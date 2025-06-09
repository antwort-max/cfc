<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\PrdProduct;
use App\Models\PrdBrand;
use App\Models\PrdCategory;
use Illuminate\Http\Request;
use App\Models\WebThemeOption;
use App\Models\WebMenu;
use App\Helpers\ActivityLogger; 

class CategoryProductController extends BaseEcommerceController
{
   
    public function show(PrdCategory $category)
    {
        $banner = $category;

        $products = PrdProduct::with(['family', 'subfamily', 'brand', 'category'])->where('category_id', $category->id)->paginate(20)->withQueryString();
        $categories = $products->pluck('category')->filter()->unique('id')->values();
        $families = $products->pluck('family')->filter()->unique('id')->values();
        $brands = $products->pluck('brand')->filter()->unique('id')->values();

        ActivityLogger::log(request(), 'view_category_products', [
            'category_id'   => $category->id,
            'category_name' => $category->name,
            'product_count' => $products->total(),
        ]);
            
        return view('ecommerce.products.list', compact('banner', 'products', 'families', 'brands', 'categories'))->with('currentBrand', $category);;
    }


}