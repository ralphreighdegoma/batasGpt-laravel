<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JurisprudenceController;

Route::get('/jurisprudence/{id}/upsert', [JurisprudenceController::class, 'upsertJurisprudenceToPinecone']);

Route::get('/', function () {
    return view('welcome');
});

