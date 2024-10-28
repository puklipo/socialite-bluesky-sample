<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('login', function () {
    return Socialite::driver('bluesky')->redirect();
})->name('login');

Route::get('callback', function (Request $request) {
    if ($request->missing('code')) {
        dd($request->all());
    }

    /** @var \Laravel\Socialite\Two\User $user */
    $user = Socialite::driver('bluesky')->user();

    $request->session()->put('bluesky_session', $user->session);

    dump($user->session);
})->name('bluesky.oauth.redirect');

//Route::middleware('auth')->group(function () {
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//});

//require __DIR__.'/auth.php';
