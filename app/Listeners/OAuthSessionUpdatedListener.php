<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Revolution\Bluesky\Events\OAuthSessionUpdated;

class OAuthSessionUpdatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(OAuthSessionUpdated $event): void
    {
        if (empty($event->session->did())) {
            return;
        }

//        session()->put('bluesky_session', $event->session->toArray());
//
//        $user = User::updateOrCreate([
//            'did' => $event->session->did(),
//        ], [
//            'name' => $event->session->displayName(),
//            'handle' => $event->session->handle(),
//            'avatar' => $event->session->avatar(),
//            'issuer' => $event->session->issuer(),
//            'refresh_token' => $event->session->refresh(),
//        ]);
    }
}
