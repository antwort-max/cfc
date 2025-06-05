<?php

namespace App\Http\Controllers\Sync;

use App\Models\PrdCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Routing\Controller;

class SyncCategoriesController extends Controller
{
    /**
     * Sincroniza las categorías desde la API externa:
     * GET http://164.77.188.108/api2025/index.php/erpProducts/category
     */
    public function index()
    {
        // 1. Llamada a la API externa
        $response = Http::timeout(10)
            ->retry(2, 100)
            ->get('http://164.77.188.108/api2025/index.php/erpProducts/category');

        if (! $response->ok()) {
            return response()->json([
                'error'  => 'Error al conectar con la API externa.',
                'status' => $response->status(),
                'body'   => $response->body(),
            ], 500);
        }

        // 2. Decodificar JSON
        $body    = $response->body();
        $decoded = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'error'      => 'JSON mal formado.',
                'json_error' => json_last_error_msg(),
                'raw_body'   => $body,
            ], 500);
        }

        // 3. Extraer array de categorías
        $items = $decoded['data'] ?? $decoded;
        if (! is_array($items)) {
            return response()->json([
                'error'     => 'Se esperaba un array de categorías.',
                'data_type' => gettype($items),
                'data'      => $items,
            ], 500);
        }

        // 4. Crear o actualizar con slug único
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

            // Generar slug base y asegurar su unicidad
            $baseSlug = Str::slug($name);
            $slug     = $baseSlug;
            $i        = 1;

            while (PrdCategory::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $i;
                $i++;
            }

            // Crear o actualizar el registro
            $category = PrdCategory::updateOrCreate(
                ['code' => $code],
                [
                    'name'        => $name,
                    'slug'        => $slug,
                ]
            );

            $category->wasRecentlyCreated ? $created++ : $updated++;
        }

        // 5. Respuesta final
        return response()->json([
            'message'      => 'Sincronización de categorías completada.',
            'total_source' => count($items),
            'created'      => $created,
            'updated'      => $updated,
        ]);
    }
}
