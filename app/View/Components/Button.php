<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Closure;

class Button extends Component
{
    /**
     * El tipo de botón (submit, button, etc.).
     */
    public string $type;
    public string $icon;

    public function __construct(string $type = 'button', string $icon = '')
    {
        $this->type = $type;
        $this->icon = $icon;
    }

    public function render()
    {
        return view('components.button');
    }
}
