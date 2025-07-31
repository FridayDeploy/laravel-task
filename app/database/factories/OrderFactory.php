<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => Patient::all()->random()->id,
            'order_id' => rand(1, 5),
            'name' => 'Test'.rand(1, 20),
            'value' => $this->faker->text(15),
            'reference' => $this->faker->text(20),
        ];
    }
}
