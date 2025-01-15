<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoController;

Route::get('/', [DemoController::class, 'index'])->name('index');
Route::post('fill-data-pdf', [DemoController::class, 'form'])->name('form');