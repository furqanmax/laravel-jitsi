<?php

use Illuminate\Support\Facades\Route;
use VcMeet\Jitsi\Http\Controllers\MeetingController;

Route::middleware('auth')->group(function () {
    Route::get('/meeting/create', [MeetingController::class, 'create'])->name('meeting.create');
    Route::post('/meeting/store', [MeetingController::class, 'store'])->name('meeting.store');
    Route::get('/meeting/{code}', [MeetingController::class, 'join'])->name('meeting.join');
    Route::post('/meeting/{code}/approve', [MeetingController::class, 'approve'])->name('meeting.approve');
    Route::post('/meeting/{code}/kick', [MeetingController::class, 'kick'])->name('meeting.kick');
    Route::get('/meeting/{id}/ended', [MeetingController::class, 'ended'])->name('meeting.ended');
    Route::get('/meeting/{code}/time-remaining', [MeetingController::class, 'timeRemaining']);
    Route::get('/meetings', [MeetingController::class, 'index'])->name('meetings.index');
});
