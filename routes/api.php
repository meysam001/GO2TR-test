<?php

use App\Http\Controllers\Admin\PhotoController;
use App\Http\Controllers\Admin\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth:api'], function() {
    Route::apiResource('post', PostController::class);
    Route::patch('post/{id}/active', [PostController::class, 'activePost']);
    Route::apiResource('photo', PhotoController::class);
});
