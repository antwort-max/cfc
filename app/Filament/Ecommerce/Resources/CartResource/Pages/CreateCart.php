<?php

namespace App\Filament\Ecommerce\Resources\CartResource\Pages;

use App\Filament\Ecommerce\Resources\CartResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCart extends CreateRecord
{
    protected static string $resource = CartResource::class;
}
