<?php

namespace App\Providers;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\AppRepository;
use App\Repositories\AppRepositoryInterface;
use App\Repositories\ImportRepository;
use App\Repositories\ImportRepositoryInterface;
use App\Repositories\ExportRepository;
use App\Repositories\ExportRepositoryInterface;
use App\Repositories\PasswordResetTokenRepository;
use App\Repositories\PasswordResetTokenRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
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
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(PasswordResetTokenRepositoryInterface::class, PasswordResetTokenRepository::class);
        $this->app->bind(ImportRepositoryInterface::class, ImportRepository::class);
        $this->app->bind(ExportRepositoryInterface::class, ExportRepository::class);
    }
}
