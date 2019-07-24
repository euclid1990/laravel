<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use App\Libraries\ChatApp\ChatAppManager;
use App\Libraries\ChatApp\Handlers\Slack;
use App\Libraries\ChatApp\Handlers\Chatwork;

class ChatAppProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerManager();
    }

    /**
     * Register the chatapp manager.
     *
     * @return void
     */
    protected function registerManager()
    {
        $this->app->singleton('chatapp', function ($app) {
            return tap(new ChatAppManager($app), function ($manager) {
                $this->registerHandlers($manager);
            });
        });
    }

    /**
     * Register chatapp handlers.
     *
     * @param  \App\ChatAppNotification\ChatAppManager $manager
     * @return void
     */
    protected function registerHandlers($manager)
    {
        foreach ([
            [
                'chatwork',
                Chatwork::class,
            ],
            [
                'slack',
                Slack::class,
            ],
        ] as $driver) {
            $manager->extend($driver[0], function ($app) use ($driver) {
                return $this->app->make($driver[1]);
            });
        }
    }
}
