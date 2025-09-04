<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Home');
});

// default route
Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
});