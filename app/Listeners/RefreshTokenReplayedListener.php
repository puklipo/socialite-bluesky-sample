<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Revolution\Bluesky\Events\RefreshTokenReplayed;

class RefreshTokenReplayedListener
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
    public function handle(RefreshTokenReplayed $event): void
    {
        info('replayed', $event->session->toArray());
        info('replayed', $event->response->json());
    }
}
