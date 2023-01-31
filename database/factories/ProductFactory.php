<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Country;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $country = collect(Country::all()->modelKeys());

        return [
            'name'        => $this->faker->words(rand(2, 4), true),
            'description' => $this->faker->text(),
            'country_id'  => $country->random(),
            'price'       => $this->faker->randomNumber(rand(3, 5)),
        ];
    }

    public function configure(): self
    {
        $categories = collect(Category::where('is_active', true)->get()->modelKeys());

        return $this->afterCreating(function (Product $product) use ($categories) {
            $product->categories()->sync($categories->random(rand(1, 3)));
        });
    }
}
