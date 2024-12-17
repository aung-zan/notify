<?php

namespace App\Services;

use App\Events\PushNotification;
use Illuminate\Support\Facades\Broadcast;

class ChannelService
{
    public function sendPushNotification(array $request, array $channel)
    {
        $this->configChannel($channel);

        PushNotification::dispatch($request);
    }

    private function configChannel(array $channel): void
    {
        // TODO: Implement switch case for different channels.
        $this->setPusherChannel($channel['credentials']);
        // $this->setLogChannel();
    }

    private function setLogChannel(): void
    {
        Broadcast::setDefaultDriver('log');
    }

    private function setPusherChannel(array $credentials): void
    {
        // Broadcast::setDefaultDriver('pusher');
        config(['broadcasting.default' => 'pusher']);

        config(['broadcasting.connections.pusher' => [
            'driver' => 'pusher',
            'key' => $credentials['key'],
            'secret' => $credentials['secret'],
            'app_id' => $credentials['app_id'],
            'options' => [
                'cluster' => $credentials['cluster'],
                'useTLS' => true,
            ],
        ]]);
    }
}
