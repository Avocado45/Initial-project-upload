<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitationController;

Route::post('/citations/fetch', [CitationController::class, 'fetch'])
    ->middleware('throttle:10,1');