<?php

namespace App\Filament\Resources\WrkTaskResource\Pages;

use App\Filament\Resources\WrkTaskResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateWrkTask extends CreateRecord
{

    protected static string $resource = WrkTaskResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        return $data;
    }
}
