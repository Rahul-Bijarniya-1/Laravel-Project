<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User2;

class UserCreateTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic feature test example.
     */
    /** @test */
    public function test_example(): void
    {
        try {
            $response = $this->postJson(route('user.create'), [
                'name' => 'Test_User_3',
                'resource_type' => 'transporter',
                'phone_number' => '1234567890',
            ]);

            $response->assertStatus(201)
                     ->assertJson(['message' => 'User successfully created']);

            $this->assertDatabaseHas('users2', [
                'resource_type' => 'App\\Models\\Transporter',
                'name' => 'Test_User_3',
            ]);

        } catch (\Exception $e) {
            $this->fail("An error occurred: " . $e->getMessage());

        } finally {
            $user = User2::withTrashed()->where('name', 'Test_User_3')->first();
            if ($user) {
                $user->forceDelete();
            }
        }
    }


    /** NEGATIVE TEST CASES */

    /** @test */
    public function test_fails_when_name_is_missing()
    {
        $response = $this->postJson(route('user.create'), [
            'resource_type' => 'customer',
            'phone_number' => $this->faker->phoneNumber,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function test_fails_when_resource_type_is_invalid()
    {
        $response = $this->postJson(route('user.create'), [
            'name' => $this->faker->name,
            'resource_type' => 'invalid_type',
            'phone_number' => $this->faker->phoneNumber,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('resource_type');
    }

     /** @test */
    public function test_fails_when_phone_number_is_not_a_string()
    {
        $response = $this->postJson(route('user.create'), [
            'name' => $this->faker->name,
            'resource_type' => 'customer',
            'phone_number' => 123456789,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('phone_number');
    }
}
