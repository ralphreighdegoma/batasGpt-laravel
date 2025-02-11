<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JurisprudenceController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/jurisprudence/{id}/upsert', [JurisprudenceController::class, 'upsertJurisprudenceToPinecone']);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/email/verify', function () {
    return view('auth.verify-email', [
        'url' => env('FRONTEND_URL'),
    ]);
})->middleware('auth')->name('verification.notice');

