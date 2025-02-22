<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\RoleController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('login',function(){
    return response(['status'=>false,'message'=>'You login first!','error'=>'Unathorised!']);
})->name('login');

Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);

Route::middleware(['auth:users'])->group(function () {
    Route::post('role',[RoleController::class,'create']);
});