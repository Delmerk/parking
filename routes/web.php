<?php

use App\Http\Livewire\CajonController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServicePr  ovider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Route::get('cajones', CajonController::class)->name('cajones');

// Route::get('cajones', [App\Http\Controllers\CajonController::class, 'cajones'])->name('cajones');

Route::view('cajones', 'cajones');
Route::view('tipos', 'tipos');
// Route::view('test', 'test');
