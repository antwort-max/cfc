<?php

namespace App\Http\Controllers\Sync;

use App\Models\BiDailyStockSnapshot;
use Illuminate\Support\Facades\Http;
use Illuminate\Routing\Controller;
use Carbon\Carbon;

class SyncStockController extends Controller
{
    /**
     * Sincroniza el stock diario desde la API externa:
     * GET http://164.77.188.108/api2025/index.php/erpProducts/stock
     */
    public function index()
    {
        // 1. Llamada a la API externa
        $response = Http::timeout(10)
            ->retry(2, 100)
            ->get('http://164.77.188.108/api2025/index.php/erpProducts/stock');

        if (! $response->ok()) {
            return response()->json([
                'error'  => 'Error al conectar con la API externa.',
                'status' => $response->status(),
                'body'   => $response->body(),
            ], 500);
        }

        // 2. Decodificar JSON
        $decoded = $response->json();

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'error'      => 'JSON mal formado.',
                'json_error' => json_last_error_msg(),
                'raw_body'   => $response->body(),
            ], 500);
        }

        // 3. Extraer array de stock
        $items = $decoded['data'] ?? $decoded;
        if (! is_array($items)) {
            return response()->json([
                'error'     => 'Se esperaba un array de stock.',
                'data_type' => gettype($items),
                'data'      => $items,
            ], 500);
        }

        // 4. Guardar snapshots
        $date    = Carbon::now()->toDateString(); // YYYY-MM-DD
        $created = 0;
        $updated = 0;

        foreach ($items as $item) {

            $sku = $item['product_sku'] ?? null;

            if (empty($sku)) {
             //   Log::warning('SyncStockController: omitiendo item sin SKU', $item);
                continue;
            }
            
            $snapshot = BiDailyStockSnapshot::updateOrCreate(
                [
                    'snapshot_date' => $date,
                    'product_sku'   => $item['product_sku'],
                ],
                [
                    'stock' => $item['stock'],
                    'cost'  => $item['cost'],
                    'price' => $item['price'] ?? 0,
                ]
            );

            if ($snapshot->wasRecentlyCreated) {
                $created++;
            } else {
                $updated++;
            }
        }

        // 5. Respuesta final
        return response()->json([
            'message'      => 'Sincronización de stock completada.',
            'date'         => $date,
            'total_source' => count($items),
            'created'      => $created,
            'updated'      => $updated,
        ]);
    }
}
