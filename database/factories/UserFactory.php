<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Customer;
use App\Models\Transporter;
use App\Models\User2;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = User2::class;

    public function definition(): array
    {
            $resource = $this->faker->randomElement([
                ['type' => Customer::class, 'id' => Customer::factory()->create()->id],
                ['type' => Transporter::class, 'id' => Transporter::factory()->create()->id],
            ]);
    
            return [
                'name' => $this->faker->name,
                'resource_type' => $resource['type'],
                'resource_id' => $resource['id'],
            ];
        
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    // public function unverified(): static
    // {
    //     return $this->state(fn (array $attributes) => [
    //         'email_verified_at' => null,
    //     ]);
    // }
}
