<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

    // User achievements
    Route::get('/achievement/user/{id}', 'AchievementController@userAchievements');

    // User routes
    Route::get('/users', 'UserController@index');
    Route::put('/users/{id}', 'UserController@update');
    Route::delete('/users/{id}', 'UserController@delete');

    // Role routes
    Route::get('/roles', 'RoleController@rolesIndex');
    Route::delete('/role/{idUser}/{idRole}', 'RoleController@removeRoleFromUser');
    Route::put('/role/{idUser}/{idRole}', 'RoleController@assignRoleToUser');

    // Reward codes
    Route::get('/rewardcodes', 'RewardCodeController@index');
    Route::post('/rewardcodes/{length}/{reward}', 'RewardCodeController@generateRandomCode');
    Route::get('/rewardcodes/redeem/{code}', 'RewardCodeController@redeemCode');
});

// Routes for authentication
Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');
Route::post('/logout', 'AuthController@logout');
