<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\PrdProduct;
use App\Models\PrdBrand;
use Illuminate\Http\Request;
use App\Models\WebThemeOption;
use App\Models\WebMenu;
use App\Helpers\ActivityLogger;   

class BrandProductController extends BaseEcommerceController
{
   
    public function show(PrdBrand $brand)
    {
        $banner = $brand;
        $products = PrdProduct::with(['family', 'subfamily', 'brand'])->where('brand_id', $brand->id)->paginate(20)->withQueryString();
        $categories = $products->pluck('category')->filter()->unique('id')->values();
        $families = $products->pluck('family')->filter()->unique('id')->values();
        $brands = $products->pluck('brand')->filter()->unique('id')->values();
        
        ActivityLogger::log(request(), 'view_brand_products', [
            'brand_id' => $brand->id,
            'brand_name' => $brand->name,
            'product_count' => $products->total(),
        ]);
            
        return view('ecommerce.products.list', compact('banner', 'products', 'families', 'brands', 'categories'))->with('currentBrand', $brand);;
    }


}