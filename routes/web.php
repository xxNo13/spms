<?php

use App\Http\Livewire\Dashboard;
use App\Http\Livewire\TtmaLivewire;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\ConfigureLivewire;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('dashboard');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/ttma', TtmaLivewire::class)->name('ttma');
    Route::get('/configure', ConfigureLivewire::class)->name('configure');
});