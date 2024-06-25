<?php

use App\Http\Controllers\PostApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShelfController;
use App\Http\Middleware\EnsureTokenIsValid;
use App\Http\Controllers\TripRequestController;


// Route::get('/posts', [PostApiController::class, 'index']);
// Route::get('/posts/{id}', [PostApiController::class, 'show'])->name('api.posts.show');

// Route::get('/tags', [PostApiController::class, 'tagIndex']);
// Route::get('/tags/{id}', [PostApiController::class, 'tagShow'])->name('api.tags.show');


//Route::get('/user/{id}', [UserController::class, 'show']);
Route::post('/create_user', [UserController::class, 'create']);
Route::delete('/delete_user', [UserController::class, 'delete']);
Route::put('/user/name/{id}', [UserController::class, 'updateName']);
Route::put('/user/phone/{id}', [UserController::class, 'updatePhoneNo']);

Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);


Route::post('/create_trip', [TripRequestController::class, 'store']);
Route::get('/index_trip', [TripRequestController::class, 'index']);
Route::get('/trip/{id}', [TripRequestController::class, 'show']);
Route::put('/trip/update/{id}', [TripRequestController::class,'update']);
Route::delete('/trip/delete/{id}', [TripRequestController::class,'destroy']);

// Route::middleware(['auth'])->group(function () {
// Route::post('/create_user', [UserController::class, 'createUser']);
// Route::get('get_user/{id}', [UserController::class, 'show']);
// Route::delete('/delete_user/{id}', [UserController::class, 'destroy']);
// Route::get('/get_users', [UserController::class, 'listUsers']);

// Route::post('/create_shelf', [ShelfController::class, 'createShelf']);
// Route::get('/get_shelf/{id}', [ShelfController::class, 'show']);
// Route::post('/assign_books', [ShelfController::class, 'assignBooks']);
// Route::get('/get_shelves', [ShelfController::class, 'listShelves']);
// });


