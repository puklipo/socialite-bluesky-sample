<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Revolution\Bluesky\Events\OAuthSessionRefreshing;

class OAuthSessionRefreshingListener
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
    public function handle(OAuthSessionRefreshing $event): void
    {
        if (empty($event->session->did())) {
            return;
        }

        $user = User::updateOrCreate([
            'did' => $event->session->did(),
        ], [
            'refresh_token' => $event->session->refresh(),
        ]);
    }
}
