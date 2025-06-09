<?php

namespace App\Http\Controllers\Ecommerce;

use App\Models\PrdBrand;
use App\Models\PrdProduct;
use Illuminate\Http\Request;
use App\Helpers\ActivityLogger;

class BrandProductController extends BaseEcommerceController
{
   
    public function show(Request $request, PrdBrand $brand)
    {
        $banner = $brand;

        // Eager-load 'category' junto a las demás relaciones
        $products = PrdProduct::with(['family', 'subfamily', 'brand', 'category'])
            ->where('brand_id', $brand->id)
            ->paginate(20)
            ->withQueryString();

        // Evitamos nuevas consultas al agrupar relationships ya cargadas
        $categories = $products->pluck('category')->filter()->unique('id')->values();
        $families   = $products->pluck('family')->filter()->unique('id')->values();
        $brands     = $products->pluck('brand')->filter()->unique('id')->values();

        ActivityLogger::log($request, 'view_brand_products', [
            'brand_id'     => $brand->id,
            'brand_name'   => $brand->name,
            'product_count'=> $products->total(),
        ]);

        return view('ecommerce.products.list', compact(
            'banner',
            'products',
            'families',
            'brands',
            'categories'
        ))->with('currentBrand', $brand);
    }
}