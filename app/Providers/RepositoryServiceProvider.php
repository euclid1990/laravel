<?php

namespace App\Providers;

use App\Models\User;
use App\Repo\UserRepository;
use App\Repo\UserRepositoryInterface;

class RepositoryServiceProvider
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function bind()
    {
        $this->app->singleton(UserRepository::class);
        $this->app->alias(UserRepository::class, UserRepositoryInterface::class);
        $this->app->alias(UserRepositoryInterface::class, 'users');
    }
}
