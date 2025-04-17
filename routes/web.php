<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home.index');
});
Route::get('/login', function () {
    return view('pages.account.login');
})->name('login');
Route::get('/register', function () {
    return view('pages.account.register');
})->name('register');
Route::get('/catalog', function () {
    return view('pages.catalog.index');
})->name('catalog');
