<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('auth/login', 'AuthController@login');
Route::post('auth/logout', 'AuthController@logout');

Route::apiResource('user', 'UserController', [
    'names' => [
        'store' => 'user.post',
    ]
]);

Route::apiResource('user_category', 'UserCategoryController')->only(['index']);
Route::apiResource('wallet', 'WalletController')->only(['show', 'update']);
Route::apiResource('transaction', 'TransactionController')->only(['index', 'store', 'show']);