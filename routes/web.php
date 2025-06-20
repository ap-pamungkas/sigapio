<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Middleware\AuthMiddleware;
use App\Livewire\Admin\BerandaController;
use App\Livewire\Admin\InsidenController;
use App\Livewire\Admin\InsidenShowController;
use App\Livewire\Admin\LogAktivitasController;
use App\Livewire\Admin\PerangkatController;
use App\Livewire\Admin\PetugasController;
use App\Livewire\Admin\PetugasShowController;
use App\Livewire\Admin\UserController;

use App\Livewire\Komando\BerandaController as KomandoBerandaController;
use App\Livewire\Komando\InsidenController as KomandoInsidenController;
use App\Livewire\Komando\InsidenShowController as KomandoInsidenShowController;
use App\Livewire\Auth\Login;
use App\Livewire\Komando\MapTrackingPetugas;
use App\Livewire\Komando\RegistrasiPetugasController;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;



Route::prefix('sigap-io_v_0.2')->group(function () {
    Livewire::setScriptRoute(fn($handle) => Route::get('/livewire/livewire.js', $handle));
Livewire::setUpdateRoute(fn($handle) => Route::post('/livewire/update', $handle));
});
Route::get('/login', Login::class)->name('login');


Route::get('/', function () {
    return view('welcome');
});



Route::middleware(['auth', 'role:komando'])->group(function(){
    Route::prefix('komando')->group(function(){
        Route::get('/beranda', KomandoBerandaController::class)->name('komando');
        Route::get('/tracking-petugas', PetugasController::class)->name('komando.tracking-petugas');
        Route::get('/petugas', PetugasController::class)->name('komando.petugas');
        Route::get('/petugas/registrasi', PetugasShowController::class)->name('komando.petugas.registrasi');
        Route::get('/perangkat', PerangkatController::class)->name('komando.perangkat');
        Route::get('/insiden', KomandoInsidenController::class)->name('komando.insiden');
        Route::get('/insiden/{id}', KomandoInsidenShowController::class)->name('komando.insiden.show');
        Route::get('insiden/registrasi-petugas/{insiden_id}', RegistrasiPetugasController::class)->name('komando.registrasi-petugas');
        Route::get('/insiden/tracking-petugas/{insiden_id}/', MapTrackingPetugas::class)->name('komando.insiden.tracking-petugas');
        Route::post('/logout',[AuthController::class, 'logout'])->name('komando.logout');
    });
});

Route::middleware(['auth','role:admin'])->group(function(){
Route::prefix('admin')->group(function(){
        Route::get('beranda', BerandaController::class)->name('admin.beranda');
        // Route::get('/jabatan', JabatanController::class)->name('admin.jabatan');
        Route::get('/petugas', PetugasController::class)->name('admin.petugas');
        Route::get('/petugas/{id}', PetugasShowController::class)->name('admin.petugas.show');
        Route::get('/perangkat', PerangkatController::class)->name('admin.perangkat');
        Route::get('/insiden', InsidenController::class)->name('admin.insiden');
        Route::get('/insiden/{id}', InsidenShowController::class)->name('admin.insiden.show');
        Route::get('/user', UserController::class)->name('admin.user');
        Route::get('/log-aktivitas', LogAktivitasController::class)->name('admin.logAktivitas');
        Route::post('/logout',[AuthController::class, 'logout'])->name('logout');
});
});
