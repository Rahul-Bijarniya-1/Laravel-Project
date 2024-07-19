<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User2;
use Illuminate\Support\Facades\DB;

class UserDeletionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_deletes_a_user()
    {
        try {
            $new_user =  User2::factory()->create();
            //dd($new_user->user_id);
            $response = $this->deleteJson(route('user.delete'), ['user_id' => $new_user->user_id]);
            //$response->dd();

            $response->assertStatus(200)
                     ->assertJson(['message' => 'User deleted successfully']);

            $this->assertSoftDeleted('users2', ['user_id' => $new_user->user_id]);
        } catch (\Exception $e) {
            $this->fail("An error occurred: " . $e->getMessage());

        } finally {
            User2::withTrashed()->where('user_id', $new_user->user_id)->forceDelete();
        }
    }

    /** NEGATIVE TEST CASES */

    /** @test */
    public function test_returns_not_found_when_deleting_non_existent_user()
    {
        $response = $this->deleteJson(route('user.delete'), ['user_id' => 999]);

        $response->assertStatus(422)
                 ->assertJson(['message' => 'The selected user id is invalid.']);
    }

    /** @test */
    public function test_returns_validation_error_for_invalid_data()
    {
        // Sending a non-integer user_id
        $response = $this->deleteJson(route('user.delete'), ['user_id' => 'invalid-id']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['user_id']);
    }

    /** @test */
    public function test_returns_validation_error_for_missing_user_id()
    {
        $response = $this->deleteJson(route('user.delete'), []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['user_id']);
    }
}
