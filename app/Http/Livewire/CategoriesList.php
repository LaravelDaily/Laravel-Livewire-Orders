<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
use Illuminate\Contracts\View\View;

class CategoriesList extends Component
{
    use WithPagination;

    public Category $category;

    public function render(): View
    {
        $categories = Category::paginate(10);

        return view('livewire.categories-list', [
            'categories' => $categories,
        ]);
    }
}
