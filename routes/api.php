<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadImageController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/upload-images', [UploadImageController::class, 'uploadImages']);
Route::get('/get-images', [UploadImageController::class, 'getImages']);
Route::post('/update-images', [UploadImageController::class, 'updateImage']);
Route::post('/delete-images', [UploadImageController::class, 'deleteImage']);
