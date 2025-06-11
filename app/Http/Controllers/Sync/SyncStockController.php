<?php

namespace App\Http\Controllers\Sync;

use App\Models\BiDailyStockSnapshot;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class SyncStockController extends Controller
{
    public function index()
    {
        // 1. Leer API
        $items = Http::timeout(10)
            ->retry(2, 200)
            ->get('http://164.77.188.108/api2025/index.php/erpProducts/stock')
            ->json('data') ?? [];

        $today    = Carbon::today()->toDateString();
        $created  = 0;
        $updated  = 0;
        $skipped  = 0;

        foreach ($items as $item) {
            $sku   = $item['product_sku'] ?? null;
            $stock = (int)   ($item['stock']  ?? 0);
            $price = (float) ($item['price']  ?? 0);
            $cost  = (float) ($item['cost']   ?? 0);

            if (! $sku) {            // sin SKU => saltar
                $skipped++;
                continue;
            }

            // 2. ¿Ya hay snapshot de HOY?
            $todaySnap = BiDailyStockSnapshot::where('product_sku', $sku)
                ->whereDate('snapshot_date', $today)
                ->first();

            if ($todaySnap) {
                // Comparamos con el snapshot de hoy
                $hasChanges =
                    $todaySnap->stock !== $stock ||
                    bccomp($todaySnap->price, $price, 2) !== 0 ||
                    bccomp($todaySnap->cost,  $cost,  2) !== 0;

                if ($hasChanges) {
                    $todaySnap->update([
                        'stock' => $stock,
                        'price' => $price,
                        'cost'  => $cost,
                    ]);
                    $updated++;
                } else {
                    $skipped++;
                }

                continue;           // pasamos al siguiente SKU
            }

            // 3. No hay snapshot de hoy → buscar el último
            $prevSnap = BiDailyStockSnapshot::where('product_sku', $sku)
                ->orderByDesc('snapshot_date')
                ->first();

            $needsNew =
                ! $prevSnap ||                      // SKU nuevo
                $prevSnap->stock !== $stock ||
                bccomp($prevSnap->price, $price, 2) !== 0 ||
                bccomp($prevSnap->cost,  $cost,  2) !== 0;

            if (! $needsNew) {
                $skipped++;
                continue;
            }

            // 4. Creamos snapshot de hoy
            BiDailyStockSnapshot::create([
                'snapshot_date' => $today,
                'product_sku'   => $sku,
                'stock'         => $stock,
                'price'         => $price,
                'cost'          => $cost,
            ]);

            $created++;
        }

        return response()->json([
            'message'   => 'Sync OK',
            'date'      => $today,
            'api_items' => count($items),
            'created'   => $created,
            'updated'   => $updated,
            'skipped'   => $skipped,
        ]);
    }
}
