<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware(['api.key.auth'])->group(function () {
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories', [CategoryController::class, 'view']);

    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/categories/{category_id}/products', [ProductController::class, 'fetchProductsByCategory']);
    Route::get('/products', [ProductController::class, 'view']);

    Route::get('/profile', [UserController::class, 'profile']);
});


Route::group(
    ['prefix' => 'webhook'],
    function () {
        Route::post('/github', [WebhookController::class, 'processGithubWebHookRequest']);
    }
);