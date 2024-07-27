<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Loader extends Component
{
    /**
     * Create a new component instance.
     */
    public $id;
    public function __construct($id = null)
    {
        $this->id = $id ?: 'loader';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.loader');
    }
}
