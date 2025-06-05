<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebLocation;
use App\Models\WebBanner;
use App\Models\WebThemeOption;
use App\Models\WebMenu;           

class NavigationController extends Controller
{

    protected $themeOptions;

    public function __construct()
    {

        $this->themeOptions = WebThemeOption::query()
            ->where('status', true)
            ->latest('id')
            ->first();

        view()->share('themeOptions', $this->themeOptions);
    }

    /** ----------------------------------------------------------------
     *  Landing Page
     * ---------------------------------------------------------------*/
    public function landingPage()
    {
        /* Banners activos */
        $banners = WebBanner::query()
            ->where('status', true)
            ->orderBy('position')
            ->get();

        /* Menú principal (solo raíz) */
        $menus = WebMenu::query()
            ->with('children')           // carga hijos para dropdowns
            ->where('status', true)
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        return view('ecommerce.landingPage.index', compact('banners', 'menus'));
    }

    /** ----------------------------------------------------------------
     *  Locales (mapa + filtros)
     * ---------------------------------------------------------------*/
    public function locationPage(Request $request)
    {
        $cityFilter = $request->query('city');
        $typeFilter = $request->query('type');

        $locations = WebLocation::query()
            ->when($cityFilter, fn ($q) => $q->where('city', $cityFilter))
            ->when($typeFilter, fn ($q) => $q->where('type', $typeFilter))
            ->latest()
            ->paginate(6)
            ->withQueryString();

        $cities = WebLocation::select('city')->distinct()->orderBy('city')->pluck('city');
        $types  = WebLocation::select('type')->distinct()->orderBy('type')->pluck('type');

        return view('ecommerce.locationPage.index', compact('locations', 'cities', 'types'));
    }
}