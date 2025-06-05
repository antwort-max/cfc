<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    'name'            => env('APP_NAME', 'Laravel'),
    'env'             => env('APP_ENV', 'production'),
    'debug'           => (bool) env('APP_DEBUG', false),
    'url'             => env('APP_URL', 'http://localhost'),
    'asset_url'       => env('ASSET_URL', null),

    /* ───── Localización ─────────────────────────────────────────────── */
    'timezone'        => 'America/Santiago',
    'locale'          => env('APP_LOCALE', 'es'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'es'),
    'faker_locale'    => env('APP_FAKER_LOCALE', 'en_US'),

    /* ───── Encriptación ─────────────────────────────────────────────── */
    'key'            => env('APP_KEY'),
    'cipher'         => 'AES-256-CBC',
    'previous_keys'  => [...array_filter(explode(',', env('APP_PREVIOUS_KEYS', '')))],

    /* ───── Modo mantenimiento ───────────────────────────────────────── */
    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store'  => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    /* ───── Service Providers ────────────────────────────────────────── */
    'providers' => ServiceProvider::defaultProviders()->merge([

        /*
         * Application Service Providers…
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\ViewServiceProvider::class,
        Barryvdh\DomPDF\ServiceProvider::class,

    ])->toArray(),

    /* ───── Facades / Aliases ────────────────────────────────────────── */
    'aliases' => Facade::defaultAliases()->merge([
        // 'Example' => App\Facades\Example::class,
        'Pdf' => Barryvdh\DomPDF\Facade::class,
    ])->toArray(),

];
