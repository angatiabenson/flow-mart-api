<?php

use App\Http\Controllers\Api\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/me', function (Request $request) {
    return response()->json([
        "name" => "Peshy",
        "phone" => "071234567890"
    ], 200);
});


Route::group(
    ['prefix' => 'webhook'],
    function () {
        Route::post('/github', [WebhookController::class, 'processGithubWebHookRequest']);
    }
);