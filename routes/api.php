<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\passportAuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//routes for api
Route::post('register',[passportAuthController::class,'userRegistration']);
Route::post('login',[passportAuthController::class,'userLogin']);
//add middleware for authorized user
Route::middleware('auth:api')->group(function(){
    Route::get('product', [passportAuthController::class,'authorizedUserDetails']);
    Route::post('/logout', [passportAuthController::class,'logout']);
});
