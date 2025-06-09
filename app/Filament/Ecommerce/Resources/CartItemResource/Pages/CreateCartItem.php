<?php

namespace App\Filament\Ecommerce\Resources\CartItemResource\Pages;

use App\Filament\Ecommerce\Resources\CartItemResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCartItem extends CreateRecord
{
    protected static string $resource = CartItemResource::class;
}
