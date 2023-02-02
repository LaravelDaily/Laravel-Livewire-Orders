<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Country;
use App\Models\Product;
use App\Models\Category;
use Livewire\Redirector;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ProductForm extends Component
{
    public Product $product;

    public bool $editing = false;

    public array $categories = [];

    public array $listsForFields = [];

    public function mount(Product $product): void
    {
        $this->product = $product;

        $this->initListsForFields();

        if ($this->product->exists) {
            $this->editing = true;

            $this->product->price = number_format($this->product->price / 100, 2);

            $this->categories = $this->product->categories()->pluck('id')->toArray();
        }
    }

    public function save(): RedirectResponse|Redirector
    {
        $this->validate();

        $this->product->price = $this->product->price * 100;

        $this->product->save();

        $this->product->categories()->sync($this->categories);

        return redirect()->route('products.index');
    }

    public function render(): View
    {
        return view('livewire.product-form');
    }

    protected function rules(): array
    {
        return [
            'product.name' => ['required', 'string'],
            'product.description' => ['required'],
            'product.country_id' => ['required', 'integer', 'exists:countries,id'],
            'product.price' => ['required'],
            'categories' => ['required', 'array']
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['countries'] = Country::pluck('name', 'id')->toArray();

        $this->listsForFields['categories'] = Category::where('is_active', true)->pluck('name', 'id')->toArray();
    }
}
