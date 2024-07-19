<?php

namespace Tests\Feature;

use App\Models\User2;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Illuminate\Http\Resources\Json\JsonResource;

class UserIndexTest extends TestCase{
    
    use RefreshDatabase;
   /** @test */
   public function test_can_list_users(): void
   {
        // DB::listen(function ($query) {
        //     Log::info($query->sql, $query->bindings);
        // });

        try{
            $customers = Customer::factory()->count(5)->create();
            foreach ($customers as $customer) {
                User2::factory()->create([
                'resource_type' => Customer::class,
                'resource_id' => $customer->id,
                ]);
            }

            //dd($customers);
            $response = $this->getJson(route('user.index'));

            $response->assertStatus(200);
            //dd();
            $response->assertJsonCount(5, 'data');
            $response->assertJsonStructure([
                'data' => [
                '*' => ['user_id', 'name', 'resource_type', 'resource_id', 'phone_number']
                ]
            ]);

        } catch (\Exception $e) {
            $this->fail("An error occurred: " . $e->getMessage());

        } finally {

            
            $userIds = User2::whereIn('resource_id', $customers->pluck('id'))->pluck('user_id');
            User2::withTrashed()->whereIn('user_id', $userIds)->forceDelete();

            $customerIds = Customer::withTrashed()->whereIn('id', $customers->pluck('id'));
            $customerIds->forceDelete();
        }
        
    }
}
