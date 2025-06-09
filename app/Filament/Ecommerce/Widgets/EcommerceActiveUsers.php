<?php

namespace App\Filament\Ecommerce\Widgets;

use App\Models\WebActivity;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class EcommerceActiveUsers extends StatsOverviewWidget
{
    protected static ?string $pollingInterval = '30s'; // actualiza cada 30 seg

    protected function getCards(): array
    {
        $activeSince = now()->subSeconds(60);

        $onlineUsers = WebActivity::query()
            ->where('created_at', '>=', $activeSince)
            ->select(DB::raw('COALESCE(user_id, session_id) as visitor'))
            ->distinct()
            ->count();

        return [
            Card::make('Usuarios activos ahora', $onlineUsers)
                ->description('En los últimos 60 segundos')
                ->color('success'),
        ];
    }
}
