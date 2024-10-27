<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware(['api.key.auth'])->group(function () {
    Route::controller(CategoryController::class)->group(function () {
        Route::post('/categories', 'store');
        Route::get('/categories', 'view');
        Route::put('/categories/{id}', 'update');
        Route::delete('/categories/{id}', 'delete');
    });

    Route::controller(ProductController::class)->group(function () {
        Route::post('/products', 'store');
        Route::get('/categories/{category_id}/products', 'fetchProductsByCategory');
        Route::get('/products', 'view');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/profile', 'profile');
    });
});


Route::group(
    ['prefix' => 'webhook'],
    function () {
        Route::post('/github', [WebhookController::class, 'processGithubWebHookRequest']);
    }
);