<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UniFiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SetupController;
use Illuminate\Support\Facades\Route;

Route::get('/setup', [SetupController::class, 'index'])->name('setup.index');
Route::post('/setup', [SetupController::class, 'store'])->name('setup.store');

Route::get('/', function () {
    if (\App\Models\User::count() === 0) {
        return redirect()->route('setup.index');
    }
    return redirect()->route('login');
});

Route::get('/dashboard', [UniFiController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/networks', [UniFiController::class, 'networks'])
    ->middleware(['auth', 'verified'])
    ->name('unifi.networks');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [AdminController::class, 'index'])->name('users.index');
    Route::post('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users/role', [AdminController::class, 'updateRole'])->name('users.role');
    Route::post('/users/sites', [AdminController::class, 'toggleSitePermission'])->name('users.sites');
    Route::post('/users/routers', [AdminController::class, 'toggleRouterPermission'])->name('users.routers');
    Route::post('/users/wifi', [AdminController::class, 'toggleWifiPermission'])->name('users.wifi');
    Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('users.destroy');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');

    Route::get('/hotspot', [\App\Http\Controllers\HotspotController::class, 'index'])->name('hotspot.index');
    Route::post('/hotspot/users', [\App\Http\Controllers\HotspotController::class, 'store'])->name('hotspot.users.store');

    Route::get('/routers', [\App\Http\Controllers\Admin\RouterController::class, 'index'])->name('routers.index');
    Route::post('/routers', [\App\Http\Controllers\Admin\RouterController::class, 'store'])->name('routers.store');
    Route::delete('/routers/{router}', [\App\Http\Controllers\Admin\RouterController::class, 'destroy'])->name('routers.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/wifi', [UniFiController::class, 'indexWifi'])->name('unifi.wifi');
    Route::post('/wifi/update', [UniFiController::class, 'updateWifi'])->name('unifi.wifi.update');
    
    Route::get('/devices', [UniFiController::class, 'indexDevices'])->name('unifi.devices');
    Route::post('/devices/mac-filter', [UniFiController::class, 'addMacFilter'])->name('unifi.devices.mac-filter');
    Route::post('/select-site', [UniFiController::class, 'selectSite'])->name('unifi.select-site');

    Route::get('/wifi/{wlan_id}/mac-filters', [UniFiController::class, 'indexMacFilters'])->name('unifi.wifi.mac-filters');
    Route::post('/wifi/mac-filters/remove', [UniFiController::class, 'removeMacFilter'])->name('unifi.wifi.mac-filters.remove');
});

require __DIR__.'/auth.php';
