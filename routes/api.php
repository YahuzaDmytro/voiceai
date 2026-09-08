<?php

use App\Models\Call;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/calls/{call}/complete', function (Call $call, Request $request) {
    abort_unless(
        $request->bearerToken() === config('livekit.laravel_api_key'),
        401
    );

    $call->lead->update($request->only('first_name', 'last_name'));
    $call->lead->update(['status' => 'qualified']);
    $call->update(['status' => 'completed', 'ended_at' => now()]);
});
