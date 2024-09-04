<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);


Route::group(
    ['prefix' => 'webhook'],
    function () {
        Route::post('/github', [WebhookController::class, 'processGithubWebHookRequest']);
    }
);