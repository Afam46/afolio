<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AiTerminalController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/contact', [ContactController::class, 'send']);

Route::post('/ai-message', [AiController::class, 'generate']);

Route::post('/ai-terminal', [AiTerminalController::class, 'generate']);