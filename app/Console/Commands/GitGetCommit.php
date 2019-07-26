<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GitGetCommit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'git:get-commit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get current commit hash and write into env file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $hash = exec('cd ' . base_path() . ' && git rev-parse --short HEAD');

        if (! $this->writeNewEnvironmentFileWith($hash)) {
            return;
        }

        $this->info('App version set successfully.');
    }

    /**
     * Write a new environment file with the given key.
     *
     * @param  string  $key
     * @return void
     */
    protected function writeNewEnvironmentFileWith($version)
    {
        file_put_contents($this->laravel->environmentFilePath(), preg_replace(
            '/^APP_VERSION=(\s*|.+)$/m',
            'APP_VERSION=' . $version,
            file_get_contents($this->laravel->environmentFilePath())
        ));
    }
}
