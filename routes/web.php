<?php

use App\Http\Controllers\ProfileController;
use App\Models\User;
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

    /** @var \Laravel\Socialite\Two\User $socialite_user */
    $socialite_user = Socialite::driver('bluesky')->user();

    /** @var \Revolution\Bluesky\Session\OAuthSession $session */
    $session = $socialite_user->session;

    $request->session()->put('bluesky_session', $session);

    dump($session);

    $user = User::updateOrCreate([
        'did' => $socialite_user->id,
    ], [
        'name' => $session->displayName(),
        'handle' => $session->handle(),
        'issuer' => $session->issuer(),
        'avatar' => $session->avatar(),
        'refresh_token' => $session->refresh(),
    ]);

    auth()->login($user, remember: true);

    return to_route('dashboard');
})->name('bluesky.oauth.redirect');

//Route::middleware('auth')->group(function () {
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//});

//require __DIR__.'/auth.php';
