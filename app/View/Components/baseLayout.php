<?php

namespace App\View\Components;

use Illuminate\View\Component;

class baseLayout extends Component
{
    /**
     * The alert type.
     *
     * @var string
     */
    public $scrollspy;


    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($scrollspy)
    {

        $this->scrollspy = $scrollspy;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.base-layout');
    }
}
