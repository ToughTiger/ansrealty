<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('homepage');
});

Route::get('/properties', function () {
    return view('properties');
});

Route::get('/contact', function () {
    return view('homepage'); // Temporary - will create proper page later
});

Route::get('/featured-project', function () {
    return view('featured-project');
});

Route::get('/about', function () {
    return view('homepage'); // Temporary - will create proper page later
});
