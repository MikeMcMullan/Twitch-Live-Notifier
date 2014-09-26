<?php

namespace Twitch;

use Illuminate\Support\ServiceProvider;
use PushBullet;
use ritero\SDK\TwitchTV\TwitchSDK;

class TwitchServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerTwitchSDK();

        $this->registerPushBulletNotifier();

        $this->setNotifier();
    }

    /**
     * Register the Twitch SDK
     */
    private function registerTwitchSDK()
    {
        $this->app->bind('ritero\SDK\TwitchTV\TwitchSDK', function($app)
        {
            $config = [
                'client_id'      => 'tjflk77ss75ofi77ipm839yt4vac7r6',
                'client_secret'  => 'rbqsbsipjxhh417oh9spic0fd4x2s45',
                'redirect_uri'   => 'http://localhost:8000/login'
            ];

            return new TwitchSDK($config);
        });

        $this->app->bind('TwitchSDK', 'ritero\SDK\TwitchTV\TwitchSDK');
    }

    /**
     * Register the PushBullet Notifier
     */
    private function registerPushBulletNotifier()
    {
        $this->app->bind('PushBullet', function($app)
        {
            return new PushBullet($app->make('config')->get('services.pushbullet.api-key'));
        });
    }

    /**
     * Set which Notifier is to be used.
     */
    private function setNotifier()
    {
        $this->app->bind('Twitch\Notifier', 'Twitch\Notifiers\PushBullet');
    }
}