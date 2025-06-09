<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\WebArea;
use App\Models\PrdBrand;
use Illuminate\Http\Request;
use App\Models\PrdProduct;

class AreaCategoryProductController extends Controller
{
    public function show(string $slug)
    {
        
        $area = WebArea::where('slug', $slug)->firstOrFail();
        $categories = $area->categories()->orderBy('name')->get();
     
        $categoryIds = $categories->pluck('id')->toArray();

        $products = PrdProduct::with(['family', 'subfamily', 'brand', 'category'])
            ->whereIn('category_id', $categoryIds)
            ->paginate(20)
            ->withQueryString();
        $brands = $products->pluck('brand')->filter()->unique('id')->values();


        return view('ecommerce.areaPage.area_detail', [
            'banner'     => $area,
            'categories' => $categories,
            'brands'     => $brands,
            'products'   => $products,
        ]);
    }
}
