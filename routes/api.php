<?php

use Illuminate\Support\Facades\Route;
use VcMeet\Jitsi\Http\Controllers\MeetingController;

// API routes for meeting functionality
Route::middleware('auth')->group(function () {
    Route::get('/api/meeting/{code}/time-remaining', [MeetingController::class, 'timeRemaining']);
});
