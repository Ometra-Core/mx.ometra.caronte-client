<?php

use Equidna\Caronte\Http\Controllers\CaronteController;
use Illuminate\Support\Facades\Route;

//webhook endpoint
Route::post('/synchronize', [CaronteController::class, 'synchronizeData']);
