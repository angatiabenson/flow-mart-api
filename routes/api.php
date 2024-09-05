<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware(['api.key.auth'])->group(function () {
    Route::post('/categories', [CategoryController::class, 'store']);
});


Route::group(
    ['prefix' => 'webhook'],
    function () {
        Route::post('/github', [WebhookController::class, 'processGithubWebHookRequest']);
    }
);