<?php 

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\PrdProduct;
use App\Models\WebThemeOption;
use App\Models\WebMenu;
use App\Helpers\ActivityLogger; 

class ProductShowController extends BaseEcommerceController
{

    public function show(PrdProduct $product)
    {
        ActivityLogger::log(request(), 'view_product', [
            'product_id' => $product->id,
            'sku'        => $product->sku,
            'name'       => $product->name,
            'price'      => $product->price ?? null,
        ]);

        return view('ecommerce.products.show', compact('product'));
    }
}
