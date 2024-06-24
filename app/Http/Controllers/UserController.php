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
use App\Http\Requests\UpdateUserNameRequest;
use App\Http\Requests\UpdatePhoneNumberRequest;
use App\Models\User2;
use App\Models\Customer;
use App\Models\Transporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function create(CreateUserRequest $request)
    {
        $validated = $request->validated();
                
        DB::transaction(function () use ($validated) {
            if ($validated['resource_type'] === 'customer') {
                $resource = Customer::create(['phone_number' => $validated['phone_number']]);
            } else {
                $resource = Transporter::create(['phone_number' => $validated['phone_number']]);
            }

            User2::create([
                'name' => $validated['name'],
                'resource_type' => get_class($resource),
                'resource_id' => $resource->id,
            ]);
        });

        return response()->json([
            'message' => 'User successfully created',
        ]);
    }

    public function delete(DeleteUserRequest $request){

        $validated = $request->validated();

        if (!($validated['user_id'])) {
            $user = User2::findOrFail($validated['user_id']);
        } else {
            $resourceType = $validated['resource_type'] === 'customer' ? Customer::class : Transporter::class;
            $user = User2::where('resource_type', $resourceType)
                        ->where('resource_id', $validated['resource_id'])
                        ->firstOrFail();
        }

        // Only delete the user, not the related resource
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    public function updateName(UpdateUserNameRequest $request, $id){
        $validated = $request->validated();

        $user = User2::findOrFail($id);
        $user->update(['name' => $validated['name']]);

        return response()->json(['message' => 'User name updated successfully'], 200);
    }

    public function updatePhoneNo(UpdatePhoneNumberRequest $request, $id){
        $validated = $request->validated();

        $user = User2::findOrFail($id);

        if($user->resource_type == Customer::class) {
            $resource = Customer::findOrFail($user->resource_id);
        } else {
            $resource = Transporter::findOrFail($user->resource_id);
        }

        $resource->update(['phone_number'=> $validated['phone_number']]);

        return response()->json(['message' => 'Phone number updated successfully'], 200);
    }

    public function index()
    {
        // Get all users without trip requests
        $users = User2::all();

        return UserResource::collection($users);
    }

    public function show($id)
    {
        $user = User2::findOrFail($id);
        $user->load('resource');

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

        return new UserDetailResource($user);
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
