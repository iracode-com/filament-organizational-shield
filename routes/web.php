<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// to fix "Route [login] not defined."
Route::get('/login', fn() => redirect(route('filament.admin.auth.login')))->name('login');

Route::get('/storage-link', fn() => Artisan::call('storage:link'));

Route::get('/', fn() => to_route('filament.admin.pages.dashboard'));