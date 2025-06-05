<?php

namespace App\Http\Controllers\Sync;

use App\Models\PrdFamily;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Routing\Controller;

class SyncFamiliesController extends Controller
{
    public function index()
    {
        $response = Http::timeout(10)
            ->retry(2, 100)
            ->get('http://164.77.188.108/api2025/index.php/erpProducts/family');

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

        if (! is_array($items)) {
            return response()->json([
                'error'     => 'Se esperaba un array de familias',
                'data_type' => gettype($items),
                'data'      => $items,
            ], 500);
        }

        $created = $updated = 0;
        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $code = $item['code'] ?? null;
            $rawName = $item['name'] ?? null;

            if (! $code || ! $rawName) {
                continue;
            }

            $name = mb_convert_case($rawName, MB_CASE_TITLE, 'UTF-8'); 

            $family = PrdFamily::updateOrCreate(
                ['code' => $code],
                [
                    'name'        => $name,
                    'slug'        => Str::slug($name),
                    'description' => $item['description'] ?? null,
                    'image'       => $item['image']       ?? null,
                ]
            );

            $family->wasRecentlyCreated ? $created++ : $updated++;
        }

        return response()->json([
            'message'       => 'Sincronización completada.',
            'total_source'  => count($items),
            'created'       => $created,
            'updated'       => $updated,
        ]);
    }
}
