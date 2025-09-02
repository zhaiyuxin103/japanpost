<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Workbench\App\Http\Controllers\JapanpostController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('japanpost', JapanpostController::class);
