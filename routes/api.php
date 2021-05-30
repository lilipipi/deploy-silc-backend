<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AssetController;
use App\Models\User;

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);
Route::post('adminLogin', [RegisterController::class, 'adminLogin']);

// Get single user for frontend work, comment when done
Route::get('/user/{userId}', [UserController::class, 'show']);



Route::middleware('auth:api', 'role')->group(function () {

    // Bellow is Assets Crud

    // Show only assets for the authenticated users
    Route::middleware(['scope:admin,AM,investor,basic'])->get('/asset', [AssetController::class, 'showAll']);
    Route::middleware(['scope:admin,AM,investor'])->get('/asset/{id}', [AssetController::class, 'show']);
    Route::middleware(['scope:AM'])->post('/asset/create', [AssetController::class, 'store']);
    Route::middleware(['scope:admin,AM'])->patch('/asset/{id}', [AssetController::class, 'update']);
    Route::middleware(['scope:admin,AM'])->delete('/asset/{id}', [AssetController::class, 'delete']);

    // Show first 4 assetes for basic users to see 
    Route::middleware(['scope:basic'])->get('/basic/asset', [AssetController::class, 'basicShowAll']);

    // Get assets of a user
    Route::middleware(['scope:admin,AM,investor'])->get('/user/assets/{user_id}', [AssetController::class, 'showUserAsset']);

    // Admin only
    Route::middleware(['scope:admin'])->patch('/admin/verifyAsset/{id}', [AssetController::class, 'verify']);
    Route::middleware(['scope:admin'])->get('/admin/asset', [AssetController::class, 'adminShowAll']);
    Route::middleware(['scope:admin'])->get('/admin/asset/{id}', [AssetController::class, 'showSingleAdmin']);

    // User CRUD
    Route::middleware(['scope:admin'])->get('/users', [UserController::class, 'showall']);
    Route::middleware(['scope:admin,AM,investor,basic'])->get('/user/{userId}', [UserController::class, 'show']);
    Route::middleware(['scope:admin'])->post('/user', [UserController::class, 'create']);
    Route::middleware(['scope:admin'])->put('/user/{userId}', [UserController::class, 'update']);
    Route::middleware(['scope:admin'])->delete('/user/{userId}', [UserController::class, 'delete']);
    Route::middleware(['scope:admin,AM,investor,basic'])->put('/user/userState', [UserController::class, 'sendDocument']);

    
});