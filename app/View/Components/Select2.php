<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Select2 extends Component
{
    public function __construct(public mixed $options, public mixed $selectedOptions)
    {}

    public function render(): View
    {
        return view('components.select2');
    }
}