<?php

namespace App\Providers;

use Closure;
use Illuminate\Support\Arr;
use InvalidArgumentException;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;

use League\Flysystem\Filesystem;
use League\Flysystem\AdapterInterface;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\Cached\CachedAdapter;
use League\Flysystem\Cached\Storage\Memory as MemoryStore;

use Google\Cloud\Storage\StorageClient;
use Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter;

use App\Libraries\FileManager\FileManager;
use App\Libraries\FileManager\Handlers\Image;
use App\Libraries\FileManager\Handlers\Doc;

class FileManagerProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // resolve all disk use gcs driver
        foreach ($this->app['config']['filesystems.disks'] as $key => $config) {
            if ($config['driver'] == 'gcs') {
                Storage::extend($key, function ($app, $config) {
                    $keyFile = array_get($config, 'key_file');
                    if (is_string($keyFile)) {
                        $client = new StorageClient([
                            'projectId' => $config['project_id'],
                            'keyFilePath' => $keyFile,
                        ]);
                    }

                    if (! is_array($keyFile)) {
                        $keyFile = [];
                    }

                    $client = new StorageClient([
                        'projectId' => $config['project_id'],
                        'keyFile' => array_merge(['project_id' => $config['project_id']], $keyFile)
                    ]);

                    $bucket = $storageClient->bucket($config['bucket']);
                    $pathPrefix = array_get($config, 'path_prefix');
                    $storageApiUri = array_get($config, 'storage_api_uri');

                    return $this->adapt($this->createFlysystem(
                        new GoogleStorageAdapter($client, $config['bucket'], $pathPrefix, $storageApiUri),
                        $config
                    ));
                });
            }
        }
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
        $this->app->singleton('filemanager', function ($app) {
            return tap(new FileManager($app), function ($manager) {
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
                'image',
                Image::class,
            ],
            [
                'doc',
                Doc::class,
            ],
        ] as $driver) {
            $manager->extend($driver[0], function ($app) use ($driver) {
                return $this->app->make($driver[1]);
            });
        }
    }

    /**
     * Create a Flysystem instance with the given adapter.
     *
     * @param  \League\Flysystem\AdapterInterface  $adapter
     * @param  array  $config
     * @return \League\Flysystem\FilesystemInterface
     */
    protected function createFlysystem(AdapterInterface $adapter, array $config)
    {
        $cache = Arr::pull($config, 'cache');

        $config = Arr::only($config, ['visibility', 'disable_asserts', 'url']);

        if ($cache) {
            $adapter = new CachedAdapter($adapter, $this->createCacheStore($cache));
        }

        return new Filesystem($adapter, count($config) > 0 ? $config : null);
    }

    /**
     * Create a cache store instance.
     *
     * @param  mixed  $config
     * @return \League\Flysystem\Cached\CacheInterface
     *
     * @throws \InvalidArgumentException
     */
    protected function createCacheStore($config)
    {
        if ($config === true) {
            return new MemoryStore;
        }

        return new Cache(
            $this->app['cache']->store($config['store']),
            $config['prefix'] ?? 'flysystem',
            $config['expire'] ?? null
        );
    }

    /**
     * Adapt the filesystem implementation.
     *
     * @param  \League\Flysystem\FilesystemInterface  $filesystem
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected function adapt(FilesystemInterface $filesystem)
    {
        return new FilesystemAdapter($filesystem);
    }
}
