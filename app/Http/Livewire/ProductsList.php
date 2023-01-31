<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;
use Livewire\WithPagination;
use Illuminate\Contracts\View\View;

class ProductsList extends Component
{
    use WithPagination;

    public function render(): View
    {
        $products = Product::paginate(10);

        return view('livewire.products-list',  [
            'products' => $products,
        ]);
    }
}
