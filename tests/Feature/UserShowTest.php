<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Transporter;
use App\Models\User2;
use App\Models\TripRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserShowTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */

     /** @test */
    public function test_can_show_user_details(): void
    {
        try {
            $customer = Customer::factory()->create();
            $user = User2::factory()->create([
                    'resource_type' => Customer::class,
                    'resource_id' => $customer->id,
            ]);

            $tripRequests = TripRequest::factory()->count(3)->create([
                'customer_id' => $customer->id,
            ]);

            // $this->assertDatabaseHas('users2', ['user_id' => $user->user_id]);

            //dd($user->user_id);
            $response = $this->getJson(route('user.show', ['user_id' => $user->user_id]));
            $response->assertStatus(200);
            $response->assertJsonStructure([
                'data' => [
                    'user_id',
                    'name',
                    'resource_type',
                    'resource_id',
                    'phone_number',
                    'trip_requests'
                ]
            ]);
        } catch (\Exception $e) {
            $this->fail("An error occurred: " . $e->getMessage());

        } finally {
            User2::withTrashed()->where('user_id', $user->user_id)->forceDelete();
            Customer::withTrashed()->where('id', $customer->id)->forceDelete();
            if (!empty($tripRequestIds)) {
                TripRequest::withTrashed()->whereIn('id', $tripRequestIds)->forceDelete();
            }
        }
    }

    /** @test */
    public function test_returns_404_when_user_not_found()
    {
        $response = $this->getJson(route('user.show', ['user_id' => 999]));

        $response->assertStatus(404);
    }

    /** @test */
    public function test_returns_400_when_user_id_is_invalid()
    {
        $response = $this->getJson(route('user.show', ['user_id' => 'invalid-id']));

        $response->assertStatus(404);
    }
}
