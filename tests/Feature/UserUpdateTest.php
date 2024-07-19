<?php

namespace Tests\Feature;

use App\Models\User2;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class UserUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_can_update_user_name()
    {
        $newName = 'newName';
        $oldName = 'oldName';
        try {
            $user = User2::factory()->create(['name' => $oldName]);
        
            //$this->assertDatabaseHas('users2', ['name' => 'New Name']);

        //$user->dd();
        //dd($user->user_id);

            $response = $this->putJson(route('user.update'), [
                'user_id' => $user->user_id,
                'name' => $newName]);

            $response->assertStatus(200);
            $response->assertJson(['message' => 'User details updated successfully']);
            //dd($user->fresh()->name);
            $this->assertEquals($newName, $user->fresh()->name);

        } catch (\Exception $e) {
            $this->fail("An error occurred: " . $e->getMessage());

        } finally {
            User2::withTrashed()->where('user_id', $user->user_id)->forceDelete();
        }
    }

     /** @test */
     public function test_can_update_user_phone()
    {
        // DB::listen(function ($query) {
        //     Log::info($query->sql, $query->bindings);
        // });

        try{

            $oldPhoneNumber = '111111111';
            $newPhoneNumber = '123123123';

            $customer = Customer::factory()->create(['phone_number' => $oldPhoneNumber]);
            $user = User2::factory()->create([
                'name' => 'Test_NAME',
                'resource_type' => Customer::class,
                'resource_id' => $customer->id
            ]);

        //dd($user);
        // dd($customer->id);

            $response = $this->putJson(route('user.update'), [
                'user_id' => $user->user_id, 
                'phone_number' => $newPhoneNumber]);
                
            // dd($user->resource_type);    
            //dd($response);
            $response->assertStatus(200);
            $response->assertJson(['message' => 'User details updated successfully']);
            $this->assertEquals($newPhoneNumber, $customer->fresh()->phone_number);
        
        } catch (\Exception $e) {
            $this->fail("An error occurred: " . $e->getMessage());

        } finally {
            Customer::withTrashed()->where('id', $customer->id)->forceDelete();
            User2::withTrashed()->where('user_id', $user->user_id)->forceDelete();
        }
    }

    /** NEGATIVE TEST CASES */

    /**
     * --------------------------
     * INVALID data provider
     * ---------------------------
     */

    public function invalidDataProvider () :array
    {
        return [
            [
                ['user_id' => '', 'name' => '', 'phone_number' => ''],
                ['user_id']
            ],
            [
                ['user_id' => 'invalid-id', 'name' => ''],
                ['user_id']
            ],
            [
                ['user_id' => 99999, 'name' => 1],
                ['user_id', 'name']
            ],
            [
                ['user_id' => 9999, 'phone_number' => 1212],
                ['user_id', 'phone_number']
            ]
        ];
    }

    /**
     * @test
     * @dataProvider invalidDataProvider
     */
    public function test_validation_for_update($data, $error_fields)
    {
        try{
            $response = $this->putJson(route('user.update'), $data);
            $error_json = [];

            foreach($error_fields as $error_field){
                array_push($error_json, $error_field);
            }

            $response->assertStatus(422)->assertJsonValidationErrors($error_json, 'errors');
        } catch (\Exception $e) {
            $this->fail("An error occurred: " . $e->getMessage());
        }

    }



    /** @test */
    public function test_update_user_not_found(): void
    {
        try{

            $data = ['user_id' => 99999, 'phone_number' => '123123123'];

            $response = $this->putJson(route('user.update'), $data);

            $response->assertStatus(422);
            $response->assertJson(['message' => 'The selected user id is invalid.']);

        } catch (\Exception $e) {
            $this->fail("An error occurred: " . $e->getMessage());
        }
    }

    /** @test */
    public function test_update_user_resource_id_invalid(): void
    {
        try {
            $user = User2::factory()->create([
                'name' => 'Test_NAME',
                'resource_type' => Customer::class,
                'resource_id' => 99999 // Assume this ID does not exist
            ]);

            $response = $this->putJson(route('user.update'), [
                'user_id' => $user->user_id,
                'phone_number' => '1111111111'
            ]);

        //dd($response);

        //$response->assertStatus(404);
            $response->assertJson(['message' => 'Resource not found']);

        } catch (\Exception $e) {
            $this->fail("An error occurred: " . $e->getMessage());

        } finally {
            User2::withTrashed()->where('user_id', $user->user_id)->forceDelete();
        }
    }
    
}