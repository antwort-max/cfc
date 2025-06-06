<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebLocation;
use App\Models\WebBanner;
use App\Models\WebThemeOption;
use App\Models\WebMenu;  
use Illuminate\Support\Facades\Auth;   
use App\Helpers\ActivityLogger;   

class NavigationController extends Controller
{

    protected $themeOptions;

    public function __construct()
    {

        $this->themeOptions = WebThemeOption::query()->where('status', true)->latest('id')->first();
        view()->share('themeOptions', $this->themeOptions);
    }

    public function landingPage(Request $request)
    {
        ActivityLogger::log($request, 'visit_landing');

        $banners = WebBanner::query()->where('status', true)->orderBy('position')->get();
        $menus = WebMenu::query()->with('children')->where('status', true)->whereNull('parent_id')->orderBy('order')->get();

        return view('ecommerce.landingPage.index', compact('banners', 'menus'));
    }

    
    public function locationPage(Request $request)
    {
        $cityFilter = $request->query('city');
        $typeFilter = $request->query('type');

         ActivityLogger::log($request, 'visit_location_page', [
            'city_filter' => $cityFilter,
            'type_filter' => $typeFilter,
        ]);

        $locations = WebLocation::query()->when($cityFilter, fn ($q) => $q->where('city', $cityFilter))
            ->when($typeFilter, fn ($q) => $q->where('type', $typeFilter))->latest()->paginate(6)->withQueryString();

        $cities = WebLocation::select('city')->distinct()->orderBy('city')->pluck('city');
        $types  = WebLocation::select('type')->distinct()->orderBy('type')->pluck('type');

        return view('ecommerce.locationPage.index', compact('locations', 'cities', 'types'));
    }
}