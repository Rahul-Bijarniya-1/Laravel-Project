<?php

namespace App\Http\Controllers;

//use App\Events\UserCreated;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserDetailResource;
use App\Http\Resources\UserPhoneNumberResource;
use App\Http\Resources\TripRequestResource;
use App\Models\Book;
use App\Models\Shelf;
//use App\Models\User;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\UpdateRequest;
use App\Http\Requests\UpdatePhoneNumberRequest;
use App\Models\User2;
use App\Models\Customer;
use App\Models\Transporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

use function PHPUnit\Framework\throwException;

class UserController extends Controller
{
    public function create(CreateUserRequest $request): JsonResponse
    {
      try{          
            DB::transaction(function () use ($request) {
                if ($request['resource_type'] === 'customer') {
                    $resource = Customer::create(['phone_number' => $request['phone_number']]);
                } else {
                    $resource = Transporter::create(['phone_number' => $request['phone_number']]);
                }

                User2::create([
                    'name' => $request['name'],
                    'resource_type' => get_class($resource),
                    'resource_id' => $resource->id,
                ]);
            });

            DB::commit();
            return response()->json([
                'message' => 'User successfully created',
                ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error creating User',
                ],500);

        }
    }

    public function delete(DeleteUserRequest $request): JsonResponse
    {

        try{
            $user = User2::findOrFail($request->user_id);
            $user->delete();

            return response()->json(['message' => 'User deleted successfully'], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unable to Delete the User',
            ],500);
        }
    }

    public function update(UpdateRequest $request): JsonResponse
    {
        // $request = ['user_id'=>2, 'name' => 'dummy', 'phone_number' => '123123']

        DB::beginTransaction();

        try {
            // $user = $request->variable;
            $user = User2::find($request->user_id);

            // Log::info('Request Data:', $request->all());
        
            if(isset($request['name'])){
                $user->update(['name' => $request['name']]);
            }

            //dd($request->resource_type);

            if(isset($request['phone_number'])){
                    if($user->resource_type == Customer::class) {
                        $resource = Customer::findOrFail($user->resource_id);
                    } else if ($user->resource_type == Transporter::class) {
                        $resource = Transporter::findOrFail($user->resource_id);
                    }

                    $resource->update(['phone_number'=> $request['phone_number']]);

            }

            DB::commit();
            return response()->json(['message' => 'User details updated successfully'], 200);

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Resource not found'], 404);
        } catch (\Exception $e) {
            //$this->fail("An error occurred: " . $e->getMessage());
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
            ],500);
        }
    }
    public function index()
    {
        // Get all users without trip requests
        $users = User2::all();

        return UserResource::collection($users);
    }

    public function show($user_id)
    {
        $user = User2::findOrFail($user_id);
        $user->load('resource');

        return new UserDetailResource($user);
    }

        // $responseData = [
        //     'user_id' => $user->user_id,
        //     'name' => $user->name,
        //     'resource_type' => $user->resource_type,
        //     'resource_id' => $user->resource_id,
        //     'resource' => $user->resource->phone_number,
        //     'tripRequests' => TripRequestResource::collection($user->resource->tripRequests),
        // ];

        // Return the response as JSON
        //return response()->json($responseData);


    public function create_dummy(Request $request){

        DB::beginTransaction();

        try{
            // $customer = Customer::factory()->create(['phone_number' => 'asdfghjkl']);
            // $user = User2::factory()->create([
            //     'name' => 'Test_NAME_1',
            //     'resource_type' => Customer::class,
            //     'resource_id' => $customer->id
            // ]);
            DB::table('users2')->insert([
                'user_id' => 99,
                'name' => 'DUMMY',
                'resource_type' => Transporter::class,
                'resource_id' => '99'
                ]);

            DB::commit();

            DB::table('users2')->where('user_id','=',77)->delete();

            DB::table('users2')->where('user_id','=',66)->update(['name' => 'dummy_3']);

            throw new \Exception('Simulated error after commit.'); 

            // $transporter = Transporter::factory()->create(['phone_number' => '!@#$%^&*(']);
            // $user = User2::factory()->create([
            //     'name' => 'Test_NAME_2',
            //     'resource_type' => Transporter::class,
            //     'resource_id' => $transporter->id
            // ]);

        } catch (\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
            ],500);
            }

    }
    
 }


//     public function index() {
//         $array = [
//             [
//                 'name' => 'John',
//                 'email' => 'john123@example.com',
//                 'phone' => '1231231231'
//             ],
//             [
//                 'name' => 'Mark',
//                 'email' => 'mark789@example.com',
//                 'phone' => '7897897897'
//             ]
//         ];

//         return response()->json([
//             'message' => '2 Users found',
//             'data' => $array,
//             'status' => true
//         ], 200);
//     }

//     public function createUser(Request $request)
//     {
//         // Validate the request data
//         $validator = Validator::make($request->all(), [
//             'name' => 'required',
//             'email' => 'required|email',
//             'phone' => 'nullable|numeric', // Optional numeric phone number
//         ]);

//         // If validation fails, return 4xx error
//         if ($validator->fails()) {
//             return response()->json(['error' => $validator->errors()], 400);
//         }

//         try{
//             // Create the user in the database
//             $user = User::create([
//                 'email' => $request->input('email'),
//                 'name' => $request->input('name'),
//                 'phone' => $request->input('phone')
//             ]);

//             event(new UserCreated($user));

//             return response()->json([
//                 'message' => 'User successfully created',
//                 'user' => $user
//             ], 201);

//         } catch (\Exception $e) {
//             return response()->json([
//                 'error' => 'Server error',
//                 'message' => $e->getMessage()
//             ], 500);
//         }
//     }

//     public function show($id, Request $request) 
//     {
//         $user = User::with('shelf')->find($id);

//         if(!$user) {
//             return response()->json([
//                 'error'=> 'User not found'
//             ], 404);
//         }

//         //check if load_books filter is true
//         $loadBooks = $request->query('load_books') === 'true';

//     try {    
//         if($loadBooks){
//             $user->shelf->load('books');
//         }
        
//         return response()->json([
//             'user' => $user
//         ],200);
//     }
//     catch (\Exception $e) {
//         return response()->json([
//             'error' => 'Server error',
//             'message' => $e->getMessage()
//         ], 500);
//     }
// }

//     public function destroy($id)
//     {

//         $user = User::find($id);

//         if (!$user) {
//             return response()->json([
//                 'error' => 'User not found'
//             ], 404);
//         }

//     try{
//         // Soft delete the user
//         $user->delete();

//         return response()->json([
//             'message' => 'User successfully deleted'
//         ], 200);
//     }   
//     catch (\Exception $e) {
//         return response()->json([
//             'error' => 'Server error',
//             'message' => $e->getMessage()
//         ], 500);
//     }
// }

//     public function listUsers(Request $request)
//     {
//         $perPage = 3;

//         // Retrieve paginated users
//         $usersQuery = User::query();
//         Log::info('Raw SQL Query: ' . $usersQuery->toSql());

//         $users = User::paginate($perPage);

//         $loadBooks = $request->query('load_books' === 'true');

//         try
//         {
//             if($loadBooks){
//                 $users->shelf->load('books');
//             }

//             return UserResource::collection($users);
//         }

//         catch (\Exception $e) {
//             return response()->json([
//                 'error' => 'Server error',
//                 'message' => $e->getMessage()
//             ], 500);
//         }

//     }
