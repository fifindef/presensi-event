<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\KategoriEventController;
use App\Http\Controllers\JenisTamuController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TamuController;

/*
|--------------------------------------------------------------------------
| HALAMAN UMUM
|--------------------------------------------------------------------------
*/

Route::get('/tamu/search', [App\Http\Controllers\TamuController::class, 'search'])
    ->name('tamu.search');
    
Route::get('/', function () {
    return view('welcome');
});

Route::get('/event/create', [EventController::class, 'create']);
/*
|--------------------------------------------------------------------------
| AUTH MIDDLEWARE GROUP
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | EVENT HUB & EVENT MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::get('/event-hub', [EventController::class, 'hub'])
        ->name('event.hub');
    
    Route::delete('/event/{id}/cancel', [EventController::class, 'cancel'])->name('event.cancel');

    Route::get('/event', [EventController::class, 'index'])
        ->name('event.index');

    Route::post('/event/store', [EventController::class, 'store'])
        ->name('event.store');

    Route::post('/event/join', [EventController::class, 'join'])
        ->name('event.join');

    /*
    |--------------------------------------------------------------------------
    | ACTIVE EVENT SWITCHER
    |--------------------------------------------------------------------------
    */

    Route::get('/event/activate/{id}', [EventController::class, 'activate'])
        ->name('event.activate');
    Route::post('/event/store', [EventController::class, 'store']);
Route::put('/event/update/{id}', [EventController::class, 'update']);
Route::delete('/event/delete/{id}', [EventController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | KATEGORI EVENT
    |--------------------------------------------------------------------------
    */

    Route::get('/kategori-event', [KategoriEventController::class, 'index'])
        ->name('kategori-event.index');

    Route::post('/kategori-event', [KategoriEventController::class, 'store'])
        ->name('kategori-event.store');

    Route::put('/kategori-event/{id}', [KategoriEventController::class, 'update'])
        ->name('kategori-event.update');

    Route::delete('/kategori-event/{id}', [KategoriEventController::class, 'destroy'])
        ->name('kategori-event.destroy');

    /*
    |--------------------------------------------------------------------------
    | JENIS TAMU
    |--------------------------------------------------------------------------
    */

    Route::get('/jenis-tamu', [JenisTamuController::class, 'index'])
        ->name('jenis-tamu.index');

    Route::post('/jenis-tamu/store', [JenisTamuController::class, 'store'])
        ->name('jenis-tamu.store');

    Route::put('/jenis-tamu/update/{id}', [JenisTamuController::class, 'update'])
        ->name('jenis-tamu.update');

    Route::delete('/jenis-tamu/destroy/{id}', [JenisTamuController::class, 'destroy'])
        ->name('jenis-tamu.destroy');

    /*
    |--------------------------------------------------------------------------
    | TAMU MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::get('/tamu', [TamuController::class, 'index'])
        ->name('tamu.index');

    Route::post('/tamu/store', [TamuController::class, 'store'])
        ->name('tamu.store');

    Route::get('/tamu/{id}/edit', [TamuController::class, 'edit'])
        ->name('tamu.edit');

    Route::put('/tamu/update/{id}', [TamuController::class, 'update'])
        ->name('tamu.update');

    Route::delete('/tamu/delete/{id}', [TamuController::class, 'destroy'])
        ->name('tamu.destroy');

    /*
    |--------------------------------------------------------------------------
    | DAFTAR TAMU EVENT
    |--------------------------------------------------------------------------
    */

    Route::get('/daftar-tamu', [TamuController::class, 'daftarTamu'])
        ->name('tamu.daftar');

    /*
    |--------------------------------------------------------------------------
    | PRESENSI & SCAN (MODIFIED)
    |--------------------------------------------------------------------------
    */

    // Halaman presensi (pakai GET parameter id_event)
    Route::get('/presensi', [PresensiController::class, 'index'])
        ->name('presensi.index');

    // Simpan presensi
    Route::post('/presensi', [PresensiController::class, 'store'])
        ->name('presensi.store');

    // Fetch data presensi (AJAX jika masih dipakai)
    Route::get('/presensi/fetch', [PresensiController::class, 'fetchData'])
       ->name('presensi.fetch');

    Route::get('/presensi-fetch', [PresensiController::class, 'fetch'])
    ->name('presensi.fetch');

    // Scan QR
    Route::get('/scan', [PresensiController::class, 'scan'])
        ->name('scan');

    Route::post('/scan/proses', [PresensiController::class, 'prosesScan'])
        ->name('scan.proses');

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['verified'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

Route::delete('/presensi/{id}', [PresensiController::class, 'destroy'])
    ->name('presensi.destroy');
/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';