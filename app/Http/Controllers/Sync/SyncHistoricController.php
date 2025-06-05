<?php

namespace App\Http\Controllers\Sync;

use App\Models\HistoricProduct;
use App\Models\HistoricDocument;
use Illuminate\Support\Facades\Http;
use Illuminate\Routing\Controller;
use Carbon\Carbon;

class SyncHistoricController extends Controller
{
    /**
     * Sincroniza datos históricos de productos y documentos entre dos fechas.
     *
     * @param  string  $initial  Fecha inicial en formato YYYY-MM-DD
     * @param  string  $final    Fecha final en formato YYYY-MM-DD
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(?string $initial = null, ?string $final = null)
    {
        $summary = [];

        $initial = $initial ? Carbon::parse($initial) : now()->subDay()->format('Y/m/d'); 
        $final   = $final   ? Carbon::parse($final)   : now()->format('Y/m/d'); 
       // dd($final);
        $prodResponse = Http::timeout(10)
            ->retry(2, 100)
            ->get("http://164.77.188.108/api2025/index.php/historic/product");

        if (! $prodResponse->ok()) {
            return response()->json([
                'error'  => 'Error al conectar con la API de productos históricos.',
                'status' => $prodResponse->status(),
                'body'   => $prodResponse->body(),
            ], 500);
        }

        $prodDecoded   = $prodResponse->json();
        $prodItems     = $prodDecoded['data'] ?? [];
        $expectedProd  = $prodDecoded['record_count'] ?? count($prodItems);

        foreach ($prodItems as $item) {
            $multiplier = ($item['document_type'] === 'NCV') ? -1 : 1;
            HistoricProduct::updateOrCreate(
                [
                    'document_id' => $item['document_id'],
                    'product_id'  => $item['product_id'],
                ],
                [
                    'document_date'      => $item['document_date'],
                    'document_type'      => $item['document_type'],
                    'document_number'    => $item['document_number'],
                    'product_sku'        => trim($item['product_sku']),
                    'product_name'       => trim($item['product_name']),
                    'product_unit'       => $item['product_unit'],
                    'product_cost'       => $item['product_cost'] * $multiplier,
                    'product_price'      => $item['product_price'] * $multiplier,
                    'warehouse'          => $item['warehouse'],
                    'quantity'           => $item['quantity'] * $multiplier,
                    'total_sales_amount' => $item['total_sales_amount'] * $multiplier,
                ]
            );
        }

        $summary['products'] = [
            'expected'  => $expectedProd,
            'processed' => count($prodItems),
        ];

        //
        // 2. Sincronizar documentos históricos
        //
        $docResponse = Http::timeout(10)
            ->retry(2, 100)
            ->get("http://164.77.188.108/api2025/index.php/historic/document");

        if (! $docResponse->ok()) {
            return response()->json([
                'error'  => 'Error al conectar con la API de documentos históricos.',
                'status' => $docResponse->status(),
                'body'   => $docResponse->body(),
            ], 500);
        }

        $docDecoded  = $docResponse->json();
        $docItems    = $docDecoded['data'] ?? [];
        $expectedDoc = $docDecoded['record_count'] ?? count($docItems);

        foreach ($docItems as $item) {
            $multiplier = ($item['document_type'] === 'NCV') ? -1 : 1;
            HistoricDocument::updateOrCreate(
                ['document_id' => $item['document_id']],
                [
                    'document_date'               => $item['document_date'],
                    'document_time'               => $item['document_time'],
                    'document_type'               => $item['document_type'],
                    'document_number'             => $item['document_number'],
                    'client'                      => trim($item['client']),
                    'place'                       => $item['place'],
                    'seller'                      => $item['seller'],
                    'total_sales_amount'          => $item['total_sales_amount'] * $multiplier,
                    'total_sales_amount_with_tax' => $item['total_sales_amount_with_tax'] * $multiplier,
                ]
            );
        }

        return response()->json([
            'message' => "Sincronización",
            'total_source'  => count($docItems),
            'product'       => $expectedDoc,
            'document'       => count($docItems)
        ]);
    }
}

