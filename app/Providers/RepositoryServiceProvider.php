<?php

namespace App\Providers;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;

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
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }
}
