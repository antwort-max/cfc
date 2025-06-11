<?php

namespace App\Http\Controllers\Sync;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\PrdBrand;
use App\Models\PrdCategory;
use App\Models\PrdFamily;
use App\Models\PrdSubfamily;
use App\Models\PrdProduct;
use Illuminate\Routing\Controller;
use App\Http\Controllers; 

class SyncProductsController extends Controller
{
    public function index()
    {
        // 1) Pedimos la API
        $response = Http::timeout(10)
            ->retry(2, 100)
            ->get('http://164.77.188.108/api2025/index.php/erpProducts/products');

        if (! $response->ok()) {
            return response()->json([
                'error'  => 'Error al conectar con la API externa.',
                'status' => $response->status(),
                'body'   => $response->body(),
            ], 500);
        }

        $body = $response->body();
        $decoded = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'error'       => 'Respuesta inválida de la API (JSON mal formado).',
                'json_error'  => json_last_error_msg(),
                'raw_body'    => $body,
            ], 500);
        }

        $items = $decoded['data'] ?? $decoded;

        // 2) Precargar marcas, categorías, familias y subfamilias
        $brands = PrdBrand::all();
        $categories = PrdCategory::all();
        $families = PrdFamily::all();
        $subfamilies = PrdSubfamily::all();

        $matriz = [];

        foreach ($items as $item) {

            $sku = $item['sku'] ?? null;
            $name = $item['name'] ?? null;

            if (empty($sku) || empty($name)) {
                continue;
            }
           
            $unit = $item['unit'] ?? '';

            $unit_price = $item['unit_price'] ?? 0;
            $brand_code = $item['brand_code'] ?? null;
            $category_code = $item['category_code'] ?? null;
            $family_code = $item['family_code'] ?? null;
            $subfamily_code = $item['subfamily_code'] ?? null;
            $stock = $item['stock'] ?? 0;
            $cost = $item['cost'] ?? 0;

            // 👈 aquí saltamos los productos no deseados
            if (in_array($category_code, ['J02', 'POR', 'PIS', 'SPC'])) {
                       
                $floatNumber = 1; // por defecto
                $packageUnit = 'Caja'; // por defecto en estas categorías

            if (preg_match('/\(([\d.,]+)/', $name, $matches)) {
                $floatNumber = str_replace(',', '.', $matches[1]);
                $floatNumber = (float) $floatNumber;
            }
        
            } else { 

                $floatNumber = 1;
                $packageUnit = $unit; 
            }

            // 3.2) Limpiar nombre y generar slug
            $cleanName = preg_replace('/\s*\((st|lt|ap)\)?\s*/i', '', $name);
            $cleanName = ucfirst(strtolower(trim($cleanName)));
            $slug = Str::slug($cleanName);

            // 3.3) Buscar relaciones en memoria
            $brand = $brands->where('code', $brand_code)->first();
            $brand_id = $brand?->id;

            $category = $categories->where('code', $category_code)->first();
            $category_id = $category?->id;

            $family = $families->where('code', $family_code)->first();
            $family_id = $family?->id;

            $subfamily = $subfamilies->where('code', $subfamily_code)->first();
            $subfamily_id = $subfamily?->id;

            // 3.4) Armar el array
            $matriz[] = [
                'sku'           => $sku,
                'name'          => $cleanName,
                'slug'          => $slug,

                'category_id'   => $category_id,
                'brand_id'      => $brand_id,
                'family_id'     => $family_id,
                'subfamily_id'  => $subfamily_id,

                'unit'          => $unit,
                'unit_price'    => $unit_price,

                'package_unit'  => $packageUnit,
                'package_qty'   => $floatNumber,
                'package_price' => $floatNumber ? (int)($unit_price * $floatNumber) : 0,

                'description'   => '',
                'cost'          => $cost,
                'stock'         => $stock,

                'weight'        => 0,
                'dimension'     => '',

                'image'         => '',
                'status'        => 1,

                'meta_title'       => $cleanName . ' ' . ($brand?->name ?? '') . ' ' . ($family?->name ?? '') . ' ' . ($subfamily?->name ?? ''),
                'meta_description' => $cleanName . ' ' . ($brand?->name ?? '') . ' ' . ($family?->name ?? '') . ' ' . ($subfamily?->name ?? ''),
                'meta_keywords'    => $cleanName . ', ' . ($brand?->name ?? '') . ', ' . ($family?->name ?? '') . ', ' . ($subfamily?->name ?? ''),
                'tags' => json_encode([
                    $brand?->name ?? '',
                    $family?->name ?? '',
                    $subfamily?->name ?? '',
                ]),
            ];
        }

    $created = $updated = 0;
    foreach ($matriz as $productData) {
        // Buscamos si ya existe el producto por SKU
        $product = PrdProduct::withTrashed()->where('sku', $productData['sku'])->first();

        if ($product) {
            // Si existe, lo actualizamos
            $product->update([
                'name'             => $productData['name'],
                'slug'             => $productData['slug'],
                'category_id'      => $productData['category_id'],
                'family_id'        => $productData['family_id'],
                'subfamily_id'     => $productData['subfamily_id'],
                'brand_id'         => $productData['brand_id'],
                'unit'             => $productData['unit'],
                'previous_price'   => $product['unit_price'], // Guardamos el precio anterior],
                'unit_price'       => $productData['unit_price'],
                'package_unit'     => $productData['package_unit'],
                'package_qty'      => $productData['package_qty'],
                'package_price'    => $productData['package_price'], 
                'description'      => $productData['description'],   
                'cost'             => $productData['cost'],
                'stock'            => $productData['stock'],
                'weight'           => $productData['weight'],
                'dimensions'       => $productData['dimension'],
                'image'            => $productData['image'],
                'status'           => $productData['status'],
                'meta_title'       => $productData['meta_title'],
                'meta_description' => $productData['meta_description'],
                'meta_keywords'    => $productData['meta_keywords'],
                'tags'             => $productData['tags'],
            ]);

            // Si el producto estaba eliminado (SoftDelete), lo restauramos
            if ($product->trashed()) {
                $product->restore();
            }
            
            $updated++;
        } else {
            // Si no existe, creamos uno nuevo
            PrdProduct::create([
                'sku'              => $productData['sku'],
                'name'             => $productData['name'],
                'slug'             => $productData['slug'],
                'category_id'      => $productData['category_id'],
                'family_id'        => $productData['family_id'],
                'subfamily_id'     => $productData['subfamily_id'],
                'brand_id'         => $productData['brand_id'],
                'unit'             => $productData['unit'],
                'unit_price'       => $productData['unit_price'],
                'package_unit'     => $productData['package_unit'],
                'package_qty'      => $productData['package_qty'],
                'package_price'    => $productData['package_price'], 
                'description'      => $productData['description'],   
                'cost'             => $productData['cost'],
                'stock'            => $productData['stock'],
                'weight'           => $productData['weight'],
                'dimensions'       => $productData['dimension'],
                'image'            => $productData['image'],
                'status'           => $productData['status'],
                'meta_title'       => $productData['meta_title'],
                'meta_description' => $productData['meta_description'],
                'meta_keywords'    => $productData['meta_keywords'],
                'tags'             => $productData['tags'],
            ]);

            $created++;
        }
    }
        return response()->json([
            'message'       => 'Sincronización completada.',
            'total_source'  => count($matriz),
            'created'       => $created,
            'updated'       => $updated,
        ]);
    }
}
