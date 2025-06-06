<?php

namespace App\Filament\Ecommerce\Resources\CusCustomerResource\Pages;

use App\Filament\Ecommerce\Resources\CusCustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomers extends ListRecords
{
    protected static string $resource = CusCustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
