<?php

namespace App\Filament\Ecommerce\Resources\CusCustomerResource\Pages;

use App\Filament\Ecommerce\Resources\CusCustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CusCustomerResource::class;
}
