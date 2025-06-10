<?php

namespace App\Http\Controllers\Ecommerce;

use App\Models\PrdProduct;
use App\Models\PrdBrand;
use App\Models\PrdCategory;
use App\Models\PrdFamily;
use App\Models\WebArea;
use Illuminate\Http\Request;
use App\Helpers\ActivityLogger;

class ProductListingController extends BaseEcommerceController
{
    public function byCategory(Request $request, PrdCategory $category)
    {
        return $this->list(
            PrdProduct::where('category_id', $category->id),
            'view_category_products',
            [
                'category_id'   => $category->id,
                'category_name' => $category->name,
            ],
            compact('category')
        );
    }

    public function byFamily(Request $request, PrdFamily $family)
    {
        return $this->list(
            PrdProduct::where('family_id', $family->id),
            'view_family_products',
            [
                'family_id'   => $family->id,
                'family_name' => $family->name,
            ],
            compact('family')
        );
    }

    public function byBrand(Request $request, PrdBrand $brand)
    {
        $productQ = PrdProduct::where('brand_id', $brand->id);

        return $this->list(
            $productQ,
            'view_brand_products',
            ['brand_id' => $brand->id, 'brand_name' => $brand->name],
            compact('brand')
        );
    }

    protected function list($query, string $event, array $meta, array $banners)
    {
        $fullQ = $query->with(['family','subfamily','brand','category']);
        $products = $fullQ->paginate(20)->withQueryString();

        // facetas
        $facets = [
            'categories' => $products->pluck('category')->unique('id')->values(),
            'families'   => $products->pluck('family')->unique('id')->values(),
            'brands'     => $products->pluck('brand')->unique('id')->values(),
        ];

        ActivityLogger::log(request(), $event, array_merge($meta, [
            'product_count' => $products->total(),
        ]));

        return view('ecommerce.products.list', array_merge(
            $banners,
            $facets,
            ['products' => $products]
        ));
    }
}