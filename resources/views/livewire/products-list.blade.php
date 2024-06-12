<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @error('orderexist')
                        <div class="p-3 mb-4 text-green-700 bg-green-200">
                            {!! $message !!}
                        </div>
                    @enderror

                    <div class="mb-4">
                        <div class="mb-4">
                            <a href="{{ route('products.create') }}"
                                class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase bg-gray-800 border border-transparent rounded-md hover:bg-gray-700">
                                Create Product
                            </a>
                        </div>

                        <button type="button" wire:click="deleteConfirm('deleteSelected')" wire:loading.attr="disabled"
                            @disabled(!$this->selectedCount)
                            class="px-4 py-2 mr-5 text-xs text-red-500 uppercase bg-red-200 border border-transparent rounded-md hover:text-red-700 hover:bg-red-300 disabled:opacity-50 disabled:cursor-not-allowed">
                            Delete Selected
                        </button>

                        <x-primary-button wire:click="export('csv')">CSV</x-primary-button>
                        <x-primary-button wire:click="export('xlsx')">XLSX</x-primary-button>
                        <x-primary-button wire:click="export('pdf')">PDF</x-primary-button>
                    </div>

                    <div class="min-w-full mb-4 overflow-hidden overflow-x-auto align-middle sm:rounded-md">
                        <table class="min-w-full border divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left bg-gray-50">
                                    </th>
                                    <th wire:click="sortByColumn('products.name')"
                                        class="px-6 py-3 text-left bg-gray-50">
                                        <span
                                            class="text-xs font-medium leading-4 tracking-wider text-gray-500 uppercase">Name</span>
                                        @if ($sortColumn == 'products.name')
                                            @include('svg.sort-' . $sortDirection)
                                        @else
                                            @include('svg.sort')
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 text-left bg-gray-50">
                                        <span
                                            class="text-xs font-medium leading-4 tracking-wider text-gray-500 uppercase">Categories</span>
                                    </th>
                                    <th wire:click="sortByColumn('countryName')" class="px-6 py-3 text-left bg-gray-50">
                                        <span
                                            class="text-xs font-medium leading-4 tracking-wider text-gray-500 uppercase">Country</span>
                                        @if ($sortColumn == 'countryName')
                                            @include('svg.sort-' . $sortDirection)
                                        @else
                                            @include('svg.sort')
                                        @endif
                                    </th>
                                    <th wire:click="sortByColumn('price')" class="w-32 px-6 py-3 text-left bg-gray-50">
                                        <span
                                            class="text-xs font-medium leading-4 tracking-wider text-gray-500 uppercase">Price</span>
                                        @if ($sortColumn == 'price')
                                            @include('svg.sort-' . $sortDirection)
                                        @else
                                            @include('svg.sort')
                                        @endif
                                    </th>
                                    <th class="px-6 py-3 text-left bg-gray-50">
                                    </th>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="px-2 py-2">
                                        <input wire:model.live.debounce="searchColumns.name" type="text"
                                            placeholder="Search..."
                                            class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                    </td>
                                    <td class="px-2 py-1">
                                        <select wire:model.live="searchColumns.category_id"
                                            class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option value="">-- choose category --</option>
                                            @foreach ($categories as $id => $category)
                                                <option value="{{ $id }}">{{ $category }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-2 py-1">
                                        <select wire:model.live="searchColumns.country_id"
                                            class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option value="">-- choose country --</option>
                                            @foreach ($countries as $id => $country)
                                                <option value="{{ $id }}">{{ $country }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-2 py-1 text-sm">
                                        <div>
                                            From
                                            <input wire:model.live.debounce="searchColumns.price.0" type="number"
                                                class="w-full mr-2 text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                        </div>
                                        <div>
                                            to
                                            <input wire:model.live.debounce="searchColumns.price.1" type="number"
                                                class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                                @foreach ($products as $product)
                                    <tr class="bg-white" wire:key="product-{{ $product->id }}">
                                        <td class="px-4 py-2 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            <input type="checkbox" value="{{ $product->id }}"
                                                wire:model.live="selected">
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            {{ $product->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            @foreach ($product->categories as $category)
                                                <span
                                                    class="px-2 py-1 text-xs text-indigo-700 bg-indigo-200 rounded-md">{{ $category->name }}</span>
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            {{ $product->country->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            ${{ number_format($product->price / 100, 2) }}
                                        </td>
                                        <td>
                                            <a href="{{ route('products.edit', $product) }}"
                                                class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase bg-gray-800 border border-transparent rounded-md hover:bg-gray-700">
                                                Edit
                                            </a>
                                            <button wire:click="deleteConfirm('delete', {{ $product->id }})"
                                                class="px-4 py-2 text-xs text-red-500 uppercase bg-red-200 border border-transparent rounded-md hover:text-red-700 hover:bg-red-300">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $products->links() }}

                </div>
            </div>
        </div>
    </div>
</div>
