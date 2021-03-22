<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('user', 'UserController');
Route::apiResource('user_category', 'UserCategoryController')->only(['index']);
Route::apiResource('wallet', 'WalletController')->only(['show', 'update']);
Route::apiResource('transaction', 'TransactionController');