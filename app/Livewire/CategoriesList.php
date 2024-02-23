<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;

class CategoriesList extends Component
{
    use WithPagination;

    public ?Category $category = null;

    public string $name = '';
    public string $slug = '';

    public Collection $categories;

    public bool $showModal = false;

    public array $active;

    public int $editedCategoryId = 0;

    public int $currentPage = 1;

	public int $perPage = 10;

    public function openModal(): void
    {
        $this->showModal = true;
    }

    public function updatedName(): void
    {
        $this->slug = Str::slug($this->name);
    }

    public function save(): void
    {
        $this->validate();

        if (is_null($this->category)) {
            $position = Category::max('position') + 1;
            Category::create(array_merge($this->only('name', 'slug'), ['position' => $position]));
        } else {
            $this->category->update($this->only('name', 'slug'));
        }

        $this->resetValidation();
        $this->reset('showModal', 'editedCategoryId');
    }

    public function cancelCategoryEdit(): void
    {
        $this->resetValidation();
        $this->reset('editedCategoryId');
    }

    public function toggleIsActive(int $categoryId): void
    {
        Category::where('id', $categoryId)->update([
            'is_active' => $this->active[$categoryId],
        ]);
    }

    public function updateOrder($list): void
    {
        foreach ($list as $item) {
            $cat = $this->categories->firstWhere('id', $item['value']);
            $order = $item['order'] + (($this->currentPage - 1) * $this->perPage);

            if ($cat['position'] != $order) {
                Category::where('id', $item['value'])->update(['position' => $order]);
            }
        }
    }

    public function editCategory(int $categoryId): void
    {
        $this->editedCategoryId = $categoryId;

        $this->category = Category::find($categoryId);
        $this->name = $this->category->name;
        $this->slug = $this->category->slug;
    }

    public function deleteConfirm(string $method, $id = null): void
    {
        $this->dispatch('swal:confirm', [
            'type'   => 'warning',
            'title'  => 'Are you sure?',
            'text'   => '',
            'id'     => $id,
            'method' => $method,
        ]);
    }

    #[On('delete')]
    public function delete($id): void
    {
        Category::findOrFail($id)->delete();
    }

    public function render(): View
    {
        $cats = Category::orderBy('position')->paginate($this->perPage);
        $links = $cats->links();
        $this->currentPage = $cats->currentPage();
        $this->categories = collect($cats->items());

        $this->active = $this->categories->mapWithKeys(
            fn (Category $item) => [$item['id'] => (bool) $item['is_active']]
        )->toArray();

        return view('livewire.categories-list', [
            'links' => $links,
        ]);
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3'],
            'slug' => ['nullable', 'string'],
        ];
    }
}
