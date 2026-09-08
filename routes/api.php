<?php

use App\Http\Controllers\AgentWebhookController;
use App\Http\Middleware\VerifyAgentKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(VerifyAgentKey::class)->group(function () {
    Route::post('/calls/{call}/complete', [AgentWebhookController::class, 'complete']);
});
