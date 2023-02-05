<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\View\View;

class OrdersList extends Component
{
    use WithPagination;

    public array $selected = [];

    public string $sortColumn = 'orders.order_date';

    public string $sortDirection = 'asc';

    public array $searchColumns = [
        'username' => '',
        'order_date' => ['', ''],
        'subtotal' => ['', ''],
        'total' => ['', ''],
        'taxes' => ['', ''],
    ];

    protected $listeners = ['delete', 'deleteSelected'];

    public function render(): View
    {
        $orders = Order::query()
            ->select(['orders.*', 'users.name as username'])
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->with('products');

        foreach ($this->searchColumns as $column => $value) {
            if (!empty($value)) {
                $orders->when($column == 'order_date', function ($orders) use ($value) {
                        if (!empty($value[0])) {
                            $orders->whereDate('orders.order_date', '>=', Carbon::parse($value[0])->format('Y-m-d'));
                        }
                        if (!empty($value[1])) {
                            $orders->whereDate('orders.order_date', '<=', Carbon::parse($value[1])->format('Y-m-d'));
                        }
                    })
                    ->when($column == 'username', fn($orders) => $orders->where('users.name', 'LIKE', '%' . $value . '%'))
                    ->when($column == 'subtotal', function ($orders) use ($value) {
                        if (is_numeric($value[0])) {
                            $orders->where('orders.subtotal', '>=', $value[0] * 100);
                        }
                        if (is_numeric($value[1])) {
                            $orders->where('orders.subtotal', '<=', $value[1] * 100);
                        }
                    })
                    ->when($column == 'taxes', function ($orders) use ($value) {
                        if (is_numeric($value[0])) {
                            $orders->where('orders.taxes', '>=', $value[0] * 100);
                        }
                        if (is_numeric($value[1])) {
                            $orders->where('orders.taxes', '<=', $value[1] * 100);
                        }
                    })
                    ->when($column == 'total', function ($orders) use ($value) {
                        if (is_numeric($value[0])) {
                            $orders->where('orders.total', '>=', $value[0] * 100);
                        }
                        if (is_numeric($value[1])) {
                            $orders->where('orders.total', '<=', $value[1] * 100);
                        }
                    });
            }
        }

        $orders->orderBy($this->sortColumn, $this->sortDirection);

        return view('livewire.orders-list', [
            'orders' => $orders->paginate(10)
        ]);
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
        Order::findOrFail($id)->delete();
    }

    public function deleteSelected(): void
    {
        $orders = Order::whereIn('id', $this->selected)->get();

        $orders->each->delete();

        $this->reset('selected');
    }

    public function getSelectedCountProperty(): int
    {
        return count($this->selected);
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
}
