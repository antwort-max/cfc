<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\WebFooterSection;
use App\Models\WebSocialLink;


class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Evitamos compartir banners cuando solo se ejecutan comandos Artisan
        if (app()->runningInConsole()) {
            return;
        }

        // ─── Banners globales ────────────────────────────────────────────────
        View::share('topBanner', [
            'status'   => true,
            'text'     => 'Nuestra Casa Matriz se encuentra en Matucana #959, Santiago, Chile',
            'bg_color' => 'bg-black',
        ]);

        View::share('saleBanner', [
            'address'       => 'Matucana #959, Santiago, Chile',
            'address.route' => 'locationPages',
            'phone'   => '+56 9 3951 8897',
        ]);

        $footer_sections = WebFooterSection::all();
        View::share('footer_sections', $footer_sections);

        $social_links = WebSocialLink::where('status', true)->get();
        View::share('social_links', $social_links);
    }

    public function register(): void
    {
        
    }
}