<?php

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


// Only authenticated users can access these routes
Route::middleware('auth:sanctum')->group(function() {

    // Get current user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Achievement routes
    Route::get('/achievement', 'AchievementController@index');
    Route::post('/achievement', 'AchievementController@store');
    Route::get('/achievement/{id}', 'AchievementController@show');
    Route::put('/achievement/{id}', 'AchievementController@update');
    Route::delete('/achievement/{id}', 'AchievementController@destroy');



});




// Routes for authentication
Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');
Route::post('/logout', 'AuthController@logout');
