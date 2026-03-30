<?php

use App\Http\Controllers\PropertyController;
use App\Http\Controllers\InquiryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('homepage');
})->name('home');

Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/{id}', [PropertyController::class, 'show'])->name('properties.show');

Route::get('/contact', [InquiryController::class, 'contact'])->name('contact');
Route::post('/inquiries', [InquiryController::class, 'store'])->name('inquiries.store');

Route::get('/featured-project', function () {
    return view('featured-project');
})->name('featured-project');

Route::get('/about', function () {
    return view('about');
})->name('about');
