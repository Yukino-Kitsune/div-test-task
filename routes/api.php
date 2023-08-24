<?php

use App\Http\Controllers\ApplicationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/requests', [ApplicationController::class, 'index']);
Route::post('/requests', function (Request $request){
    return response()->json(ApplicationController::store($request));
});
Route::put('/requests/{id}', function (Request $request, $id) {
    return response()->json(ApplicationController::update($request, $id));
});


