<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $user = collect(User::all()->modelKeys());

        return [
            'user_id'    => $user->random(),
            'order_date' => $this->faker->dateTimeBetween('-1 week', '+1 day'),
            'subtotal'   => 0,
            'taxes'      => config('app.orders.taxes'),
            'total'      => 0,
        ];
    }

    public function configure(): self
    {
        $products = Product::all('id', 'price');

        return $this->afterCreating(function (Order $order) use ($products) {
            $insert = [];
            $subtotal = 0;

            foreach ($products->random(rand(1, 2)) as $product) {
                $quantity = rand(1, 5);

                $insert[$product->id] = ['price' => $product->price, 'quantity' => $quantity];

                $subtotal += $product->price * $quantity;
            }

            $total = $subtotal * (1 + config('app.orders.taxes') / 100);

            $order->update([
                'subtotal' => $subtotal,
                'taxes'    => $total - $subtotal,
                'total'    => $total,
            ]);

            $order->products()->sync($insert);
        });
    }
}
