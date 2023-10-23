<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Chirp;

Route::view('/', 'welcome')->name('welcome');

//! routes required auth
Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/chirps', function () {
        return view('chirps.index');
    })->name('chirps.index');

    Route::post('/chirps', function () {
        // inserte in DB the message
        Chirp::create([
            'message' => request('message'),
            'user_id' => auth()->id(),
        ]);

        // show alert that message save in data base!!
        // session()->flash('status', 'Chirp created successfully!'); // mismo que ->with()

        return to_route('chirps.index')
            ->with('status', 'Chirp created successfully!');
    });
});

require __DIR__.'/auth.php';
