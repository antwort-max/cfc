<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\WebThemeOption;
use App\Models\WebMenu;

class BaseEcommerceController extends Controller
{
    protected $themeOptions;
    protected $menus;

    public function __construct()
    {
        $this->themeOptions = WebThemeOption::where('status', true)
            ->latest('id')->first();
        view()->share('themeOptions', $this->themeOptions);

        $this->menus = WebMenu::with('children')
            ->where('status', true)
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();
        view()->share('menus', $this->menus);
    }
}
