<?php

namespace App\Http\Controllers\Ecommerce;

use App\Models\PrdFamily;
use App\Models\PrdProduct;
use Illuminate\Http\Request;
use App\Helpers\ActivityLogger;

class FamilyProductController extends BaseEcommerceController
{
    /**
     * Muestra la lista de productos para una familia,
     * evitando N+1 al eager-load de todas las relaciones necesarias.
     */
    public function show(Request $request, PrdFamily $family)
    {
        $banner = $family;

        // Eager-load 'category' junto con las demás relaciones
        $products = PrdProduct::with(['family', 'subfamily', 'brand', 'category'])
            ->where('family_id', $family->id)
            ->paginate(20)
            ->withQueryString();

        // Agrupamos las relaciones ya cargadas sin consultas adicionales
        $categories = $products->pluck('category')->filter()->unique('id')->values();
        $families   = $products->pluck('family')->filter()->unique('id')->values();
        $brands     = $products->pluck('brand')->filter()->unique('id')->values();

        ActivityLogger::log($request, 'view_family_products', [
            'family_id'     => $family->id,
            'family_name'   => $family->name,
            'product_count' => $products->total(),
        ]);

        return view('ecommerce.products.list', compact(
            'banner',
            'products',
            'families',
            'brands',
            'categories'
        ))->with('currentFamily', $family);
    }
}


