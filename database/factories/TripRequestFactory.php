<?php

namespace Database\Factories;
use App\Models\TripRequest;
use App\Models\Customer;
use App\Models\Transporter;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TripRequest>
 */
class TripRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = TripRequest::class;
    public function definition(): array
    {
        $isCustomer = $this->faker->boolean;

        return [
            'customer_id' => $isCustomer ? Customer::factory() : null,
            'transporter_id' => $isCustomer ? null : Transporter::factory(),
            'source' => $this->faker->city,
            'destination' => $this->faker->city,
            'amount' => $this->faker->numberBetween(50, 500),
            'status' => $this->faker->randomElement(['pending', 'completed', 'cancelled']),
        ];
    }
}
