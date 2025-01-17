<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\UplodeController;

Route::get('/', [UplodeController::class, 'index'])->name('UplodePage');
Route::post('/upload-pdfs', [UplodeController::class, 'uploadMultiplePdfs'])->name('upload.pdf.multiple');

Route::get('/print', [DemoController::class, 'index'])->name('print');
Route::post('fill-data-pdf', [DemoController::class, 'form'])->name('form');

