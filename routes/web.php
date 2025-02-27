<?php

use Illuminate\Support\Facades\Route;
use Filament\Pages\Auth\Login;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', Login::class); // Load the Filament login page directly

