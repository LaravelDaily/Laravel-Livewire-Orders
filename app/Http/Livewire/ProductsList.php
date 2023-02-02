<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Country;
use App\Models\Category;
use Livewire\WithPagination;
use App\Exports\ProductsExport;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProductsList extends Component
{
    use WithPagination;

    public array $categories = [];

    public array $countries = [];

    public array $selected = [];

    public string $sortColumn = 'products.name';

    public string $sortDirection = 'asc';

    public array $searchColumns = [
        'name' => '',
        'price' => ['', ''],
        'description' => '',
        'category_id' => 0,
        'country_id' => 0,
    ];

    protected $queryString = [
        'sortColumn' => [
            'except' => 'products.name'
        ],
        'sortDirection' => [
            'except' => 'asc',
        ],
    ];

    protected $listeners = ['delete', 'deleteSelected'];

    public function mount(): void
    {
        $this->categories = Category::pluck('name', 'id')->toArray();
        $this->countries = Country::pluck('name', 'id')->toArray();
    }

    public function getSelectedCountProperty(): int
    {
        return count($this->selected);
    }

    public function deleteConfirm($method, $id = null): void
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'type'  => 'warning',
            'title' => 'Are you sure?',
            'text'  => '',
            'id'    => $id,
            'method' => $method,
        ]);
    }

    public function delete($id): void
    {
        $product = Product::findOrFail($id);

        if ($product->orders()->exists()) {
            $this->addError('orderexist', 'This product cannot be deleted, it already has orders');
            return;
        }

        $product->delete();
    }

    public function deleteSelected(): void
    {
        $products = Product::with('orders')->whereIn('id', $this->selected)->get();

        foreach ($products as $product) {
            if ($product->orders()->exists()) {
                $this->addError("orderexist", "Product <span class='font-bold'>{$product->name}</span> cannot be deleted, it already has orders");
                return;
            }
        }

        $products->each->delete();

        $this->reset('selected');
    }

    public function sortByColumn($column): void
    {
        if ($this->sortColumn == $column) {
            $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
        } else {
            $this->reset('sortDirection');
            $this->sortColumn = $column;
        }
    }

    public function export($format): BinaryFileResponse
    {
        abort_if(! in_array($format, ['csv', 'xlsx', 'pdf']), Response::HTTP_NOT_FOUND);

        return Excel::download(new ProductsExport($this->selected), 'products.' . $format);
    }

    public function render(): View
    {
        $products = Product::query()
            ->select(['products.*', 'countries.id as countryId', 'countries.name as countryName',])
            ->join('countries', 'countries.id', '=', 'products.country_id')
            ->with('categories');

        foreach ($this->searchColumns as $column => $value) {
            if (!empty($value)) {
                $products->when($column == 'price', function ($products) use ($value) {
                    if (is_numeric($value[0])) {
                        $products->where('products.price', '>=', $value[0] * 100);
                    }
                    if (is_numeric($value[1])) {
                        $products->where('products.price', '<=', $value[1] * 100);
                    }
                })
                ->when($column == 'category_id', fn($products) => $products->whereRelation('categories', 'id', $value))
                ->when($column == 'country_id', fn($products) => $products->whereRelation('country', 'id', $value))
                ->when($column == 'name', fn($products) => $products->where('products.' . $column, 'LIKE', '%' . $value . '%'));
            }
        }

        $products->orderBy($this->sortColumn, $this->sortDirection);

        return view('livewire.products-list',  [
            'products' => $products->paginate(10)
        ]);
    }
}
