<?php

use App\Http\Livewire\Dummy;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\OpcrLivewire;
use App\Http\Livewire\TtmaLivewire;
use App\Http\Livewire\StaffLivewire;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\FacultyLivewire;
use App\Http\Controllers\PdfController;
use App\Http\Livewire\TrainingLivewire;
use App\Http\Livewire\AssignPmtLivewire;
use App\Http\Livewire\ConfigureLivewire;
use App\Http\Livewire\ForApprovalLivewire;
use App\Http\Livewire\ListingOpcrLivewire;
use App\Http\Livewire\SubordinateLivewire;
use App\Http\Livewire\StandardOpcrLivewire;
use App\Http\Livewire\StandardStaffLivewire;
use App\Http\Livewire\ListingFacultyLivewire;
use App\Http\Livewire\StandardFacultyLivewire;
use App\Http\Livewire\RecommendationListLivewire;
use App\Http\Livewire\ListingStandardOpcrLivewire;
use App\Http\Livewire\ListingStandardFacultyLivewire;
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
    Route::get('/assign-pmt', AssignPmtLivewire::class)->name('assign.pmt');

    Route::group(['prefix' => 'ipcr', 'as' => 'ipcr.'], function() {
        Route::get('/staff', StaffLivewire::class)->name('staff');
        Route::get('/standard/staff', StandardStaffLivewire::class)->name('standard.staff');
        Route::get('/faculty', FacultyLivewire::class)->name('faculty');
        Route::get('/standard/faculty', StandardFacultyLivewire::class)->name('standard.faculty');
        Route::get('/listing/faculty', ListingFacultyLivewire::class)->name('listing.faculty');
        Route::get('/listing/standard/faculty', ListingStandardFacultyLivewire::class)->name('listing.standard.faculty');
    });

    Route::group(['prefix' => 'opcr', 'as' => 'opcr.'], function() {
        Route::get('/', OpcrLivewire::class)->name('opcr');
        Route::get('/standard', StandardOpcrLivewire::class)->name('standard');
        Route::get('/listing', ListingOpcrLivewire::class)->name('listing');
        Route::get('/listing/standard', ListingStandardOpcrLivewire::class)->name('listing.standard');
    });

    Route::group(['prefix' => 'print', 'as' => 'print.'], function() {
        Route::get('/ipcr/faculty/{id}', [PdfController::class, 'ipcrFaculty'])->name('ipcr.faculty');
        Route::get('/ipcr/staff/{id}', [PdfController::class, 'ipcrStaff'])->name('ipcr.staff');
        Route::get('/opcr/{id}', [PdfController::class, 'opcr'])->name('opcr');
        Route::get('/ttma', [PdfController::class, 'ttma'])->name('ttma');

        
        Route::get('/rankings/faculty', [PdfController::class, 'rankingFaculty'])->name('rankings.faculty');
        Route::get('/rankings/staff', [PdfController::class, 'rankingStaff'])->name('rankings.staff');
        Route::get('/rankings/{id}', [PdfController::class, 'rankingPerOffice'])->name('rankings.office');
        Route::get('/rankings/opcr', [PdfController::class, 'rankingOpcr'])->name('rankings.opcr');
    });
});