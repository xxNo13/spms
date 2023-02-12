<?php

use App\Http\Livewire\Dummy;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\TtmaLivewire;
use App\Http\Livewire\StaffLivewire;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\TrainingLivewire;
use App\Http\Livewire\ConfigureLivewire;
use App\Http\Livewire\ForApprovalLivewire;
use App\Http\Livewire\SubordinateLivewire;
use App\Http\Livewire\RecommendationListLivewire;
use App\Http\Livewire\RecommendedForTrainingLivewire;

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
    Route::get('/trainings', TrainingLivewire::class)->name('trainings');
    Route::get('/subordinates', SubordinateLivewire::class)->name('subordinates');
    Route::get('/recommendation-list', RecommendationListLivewire::class)->name('recommendation.list');
    Route::get('/recommended-for-training', RecommendedForTrainingLivewire::class)->name('recommended.for.training');
    Route::get('/for-approval', ForApprovalLivewire::class)->name('for.approval');

    Route::group(['prefix' => 'ipcr', 'as' => 'ipcr.'], function() {
        Route::get('/staff', StaffLivewire::class)->name('staff');
    });
});