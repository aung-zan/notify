<?php

namespace App\Services;

use App\Events\PushNotification;

class PushService
{
    private $info;

    /**
     * Send push notification to the channel.
     *
     * @param array $request
     * @param array $channel
     * @return void
     */
    public function sendPushNotification(array $request, array $channel): void
    {
        $this->setChannel($channel);

        $data = array_merge($request, $this->info);

        PushNotification::dispatch($data);
    }

    /**
     * Set the channel for the push notification.
     *
     * @param array $channel
     * @return void
     */
    private function setChannel(array $channel): void
    {
        if (env('APP_ENV') === 'testing') {
            $this->setLogChannel();
        } else {
            // TODO: Implement different channels inside switch case.
            switch ($channel['provider']) {
                case 1:
                    $this->setPusherChannel($channel['credentials']);
                    break;

                case 2 || 3:
                    break;

                default:
                    \Log::info('Unknown channel provider');
                    break;
            }
        }
    }

    /**
     * Set the log channel for the push notification.
     *
     * @return void
     */
    private function setLogChannel(): void
    {
        $this->info['channelName'] = 'pushNotificationLog';
        $this->info['eventName'] = 'pushNotificationLog';

        config(['broadcasting.default' => 'log']);
    }

    /**
     * Set the pusher channel for the push notification.
     *
     * @param array $credentials
     * @return void
     */
    private function setPusherChannel(array $credentials): void
    {
        $this->info['channelName'] = 'pushNotificationTest';
        $this->info['eventName'] = 'pushNotificationTest';

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
