<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Country;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Contracts\View\View;

class ProductForm extends Component
{
    public ?Product $product = null;

    public string $name = '';
    public string $description = '';
    public ?float $price;
    public ?int $country_id;

    public bool $editing = false;

    public array $categories = [];

    public array $listsForFields = [];

    public function mount(Product $product): void
    {
        $this->initListsForFields();

        if (! is_null($this->product)) {
            $this->product = $product;
            $this->editing = true;

            $this->name = $this->product->name;
            $this->description = $this->product->description;
            $this->price = number_format($this->product->price / 100, 2);
            $this->country_id = $this->product->country_id;

            $this->categories = $this->product->categories()->pluck('id')->toArray();
        }
    }

    public function save(): void
    {
        $this->validate();

        if (is_null($this->product)) {
            $this->product = Product::create(
                array_merge(
                    $this->only('name', 'description', 'country_id'),
                    ['price' => $this->price * 100]
                )
            );
        } else {
            $this->product->update(
                array_merge(
                    $this->only('name', 'description', 'country_id'),
                    ['price' => $this->price * 100]
            ));
        }

        $this->product->categories()->sync($this->categories);

        $this->redirect(route('products.index'));
    }

    public function render(): View
    {
        return view('livewire.product-form');
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['required'],
            'country_id' => ['required', 'integer', 'exists:countries,id'],
            'price' => ['required'],
            'categories' => ['required', 'array'],
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['countries'] = Country::pluck('name', 'id')->toArray();

        $this->listsForFields['categories'] = Category::where('is_active', true)->pluck('name', 'id')->toArray();
    }
}
