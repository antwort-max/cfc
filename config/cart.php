<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tax rate for cart calculations
    |--------------------------------------------------------------------------
    | Toma el valor de .env o usa 0.19 por defecto.
    */
    'tax_rate' => env('TAX_RATE', 0.19),
];
