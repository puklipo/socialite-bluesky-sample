<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Revolution\Bluesky\Facades\Bluesky;
use Revolution\Bluesky\Session\OAuthSession;

Route::get('/', function (Request $request) {
    if (app()->isLocal() & $request->has('iss')) {
        return to_route('bluesky.oauth.redirect', $request->query());
    }
    return view('welcome');
});

Route::get('bsky/login', function () {
    return Socialite::driver('bluesky')->redirect();
})->name('login');

Route::get('bsky/callback', function (Request $request) {
    if ($request->missing('code')) {
        dd($request->all());
    }

    /** @var \Laravel\Socialite\Two\User $socialite_user */
    $socialite_user = Socialite::driver('bluesky')->user();

    /** @var OAuthSession $session */
    $session = $socialite_user->session;

    $user = User::updateOrCreate([
        'did' => $session->did(),
    ], [
        'name' => $session->displayName(),
        'handle' => $session->handle(),
        'avatar' => $session->avatar(),
        'issuer' => $session->issuer(),
        'refresh_token' => $session->refresh(),
    ]);

    auth()->login($user, remember: true);

    session()->put('bluesky_session', $session->toArray());

    return to_route('dashboard');
})->name('bluesky.oauth.redirect');

Route::post('bsky/logout', function (Request $request) {
    auth()->logout();

    session()->forget('bluesky_session');

    $request->session()->invalidate();
    $request->session()->regenerate();

    return redirect('/');
})->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/first', function (Request $request) {
    $session = OAuthSession::create(session('bluesky_session'));

    $post = Bluesky::getAuthorFeed(actor: $session->did());

    $post = $post->json('feed.{first}');

    return view('dashboard')->with('post', $post);
})->middleware(['auth', 'verified'])->name('bsky.first');

Route::post('/refresh', function (Request $request) {
    //$session = OAuthSession::create(session('bluesky_session'));
    $session = OAuthSession::create();

    if ($session->tokenExpired()) {
        $session = OAuthSession::create([
            'did' => $request->user()->did,
            'issuer' => $request->user()->issuer,
            'refresh_token' => $request->user()->refresh_token,
        ]);
    }

    info('refresh', $session->toArray());

    Bluesky::withToken($session)
        ->refreshSession();

    return to_route('dashboard');
})->middleware(['auth', 'verified'])->name('bsky.refresh');

//Route::middleware('auth')->group(function () {
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//});

//require __DIR__.'/auth.php';
