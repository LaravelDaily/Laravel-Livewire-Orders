<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-screen-2xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div class="mb-4">
                        <div class="mb-4">
                            <a href="{{ route('orders.create') }}"
                                class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase bg-gray-800 border border-transparent rounded-md hover:bg-gray-700">
                                Create Order
                            </a>
                        </div>

                        <button type="button" wire:click="deleteConfirm('deleteSelected')" wire:loading.attr="disabled"
                            {{ $this->selectedCount ? '' : 'disabled' }}
                            class="px-4 py-2 mr-5 text-xs text-red-500 uppercase bg-red-200 border border-transparent rounded-md hover:text-red-700 hover:bg-red-300 disabled:opacity-50 disabled:cursor-not-allowed">
                            Delete Selected
                        </button>
                    </div>

                    <div class="min-w-full mb-4 overflow-hidden overflow-x-auto align-middle sm:rounded-md">
                        <table class="min-w-full border divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left bg-gray-50">
                                    </th>
                                    <th wire:click="sortByColumn('order_date')"
                                        class="w-40 px-6 py-3 text-left bg-gray-50">
                                        <span
                                            class="text-xs font-medium leading-4 tracking-wider text-gray-500 uppercase">Order
                                            date</span>
                                        @if ($sortColumn == 'order_date')
                                            @include('svg.sort-' . $sortDirection)
                                        @else
                                            @include('svg.sort')
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 text-left bg-gray-50">
                                        <span
                                            class="text-xs font-medium leading-4 tracking-wider text-gray-500 uppercase">User
                                            Name</span>
                                    </th>
                                    <th class="px-6 py-3 text-left bg-gray-50 w-fit">
                                        <span
                                            class="text-xs font-medium leading-4 tracking-wider text-gray-500 uppercase">Products</span>
                                    </th>
                                    <th wire:click="sortByColumn('subtotal')"
                                        class="px-6 py-3 text-left w-36 bg-gray-50">
                                        <span
                                            class="text-xs font-medium leading-4 tracking-wider text-gray-500 uppercase">Subtotal</span>
                                        @if ($sortColumn == 'subtotal')
                                            @include('svg.sort-' . $sortDirection)
                                        @else
                                            @include('svg.sort')
                                        @endif
                                    </th>
                                    <th wire:click="sortByColumn('taxes')" class="w-32 px-6 py-3 text-left bg-gray-50">
                                        <span
                                            class="text-xs font-medium leading-4 tracking-wider text-gray-500 uppercase">Taxes</span>
                                        @if ($sortColumn == 'taxes')
                                            @include('svg.sort-' . $sortDirection)
                                        @else
                                            @include('svg.sort')
                                        @endif
                                    </th>
                                    <th wire:click="sortByColumn('total')" class="w-32 px-6 py-3 text-left bg-gray-50">
                                        <span
                                            class="text-xs font-medium leading-4 tracking-wider text-gray-500 uppercase">Total</span>
                                        @if ($sortColumn == 'total')
                                            @include('svg.sort-' . $sortDirection)
                                        @else
                                            @include('svg.sort')
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 text-left w-44 bg-gray-50">
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                    </td>
                                    <td class="px-1 py-1 text-sm">
                                        <div>
                                            From
                                            <input x-data x-init="new Pikaday({
                                                field: $el,
                                                format: 'MM/DD/YYYY',
                                                onSelect: function() {
                                                    @this.set('date', $refs.pikaday.value)
                                                }
                                            })"
                                                wire:model.blur="searchColumns.order_date.0" type="text"
                                                placeholder="MM/DD/YYYY"
                                                class="w-full mr-2 text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                        </div>
                                        <div>
                                            to
                                            <input x-data x-init="new Pikaday({ field: $el, format: 'MM/DD/YYYY' })"
                                                wire:model.blur="searchColumns.order_date.1" type="text"
                                                placeholder="MM/DD/YYYY"
                                                class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                        </div>
                                    </td>
                                    <td class="px-1 py-1 text-sm">
                                        <input wire:model.live.debounce="searchColumns.username" type="text"
                                            placeholder="Search..."
                                            class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                    </td>
                                    <td class="px-1 py-1">
                                    </td>
                                    <td class="px-1 py-1 text-sm">
                                        From
                                        <input wire:model.live.debounce="searchColumns.subtotal.0" type="number"
                                            class="w-full mr-2 text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                        to
                                        <input wire:model.live.debounce="searchColumns.subtotal.1" type="number"
                                            class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                    </td>
                                    <td class="px-1 py-1 text-sm">
                                        From
                                        <input wire:model.live.debounce="searchColumns.taxes.0" type="number"
                                            class="w-full mr-2 text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                        to
                                        <input wire:model.live.debounce="searchColumns.taxes.1" type="number"
                                            class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                    </td>
                                    <td class="px-1 py-1 text-sm">
                                        From
                                        <input wire:model.live.debounce="searchColumns.total.0" type="number"
                                            class="w-full mr-2 text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                        to
                                        <input wire:model.live.debounce="searchColumns.total.1" type="number"
                                            class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                    </td>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                                @foreach ($orders as $order)
                                    <tr class="bg-white" wire:key="order-{{ $order->id }}">
                                        <td class="px-4 py-2 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            <input type="checkbox" value="{{ $order->id }}" wire:model="selected">
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            {{ $order->order_date->format('m/d/Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            {{ $order->username }}
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            @foreach ($order->products as $product)
                                                <span
                                                    class="px-2 py-1 text-xs text-indigo-700 bg-indigo-200 rounded-md">{{ $product->name }}</span>
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            ${{ number_format($order->subtotal / 100, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            ${{ number_format($order->taxes / 100, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            ${{ number_format($order->total / 100, 2) }}
                                        </td>
                                        <td>
                                            <a href="{{ route('orders.edit', $order) }}"
                                                class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase bg-gray-800 border border-transparent rounded-md hover:bg-gray-700">
                                                Edit
                                            </a>
                                            <button wire:click="deleteConfirm('delete', {{ $order->id }})"
                                                class="px-4 py-2 text-xs text-red-500 uppercase bg-red-200 border border-transparent rounded-md hover:text-red-700 hover:bg-red-300">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $orders->links() }}

                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
@endpush
