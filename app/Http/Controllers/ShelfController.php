<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShelfResource;
use App\Models\Book;
use App\Models\BookShelf;
use App\Models\Shelf;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ShelfController extends Controller
{
    public function createShelf(Request $request)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string',
        ]);

        // Check validation
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422); 
        }

        try {
            // Create shelf
            $shelf = Shelf::create([
                'user_id' => $request->input('user_id'),
                'name' => $request->input('name'),
            ]);

            return response()->json([
                'message' => 'Shelf successfully created',
                'shelf' => $shelf
            ], 201); // 201 Created
        }
        catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function show($id)
    {
        // Find the shelf by ID and load related books and user details
        $shelf = Shelf::with(['books', 'user'])->find($id);

        if (!$shelf) {
            return response()->json([
                'error' => 'Shelf not found'
            ], 404); // 404 Not Found
        }

        try{
            dump($shelf);
            return response()->json([
                'shelf' => $shelf
            ], 200); // 200 OK
        }
        catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function assignBooks(Request $request) 
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'shelf_id' => 'required|exists:shelf,id',
            'book_id' => 'required|exists:books,id',
        ]);

        // Check validation
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }


        //Check if shelf is owned by user 
        $shelf = Shelf::where('id', $request->shelf_id)
                        ->where('user_id', $request->user_id)
                        ->first();

        if(!$shelf)
        {
            return response()->json(
                [
                    'error' => 'Shelf is not owned by user'
                ], 403);
        }

        //Check if book is assigned to any shelf
        $bookAssigned = BookShelf::where('book_id', $request->book_id)->first();

        if($bookAssigned)
        {
            return response()->json([
                    'error'=>'Book already assigned to a shelf'
                ], 409);
        }

        try{
            BookShelf::create([
                'shelf_id' => $request->shelf_id,
                'book_id' => $request->book_id,
            ]);
    
            return response()->json([
                'message' => 'Book successfully assigned to the shelf'
            ], 201);
        }
        catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function listShelves(Request $request)
    {
        $perPage = 5;

        $shelves = Shelf::paginate($perPage);

        $shelves->load('books');

        return ShelfResource::collection($shelves);
    }
}
