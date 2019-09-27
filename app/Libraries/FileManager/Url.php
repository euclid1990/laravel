<?php

namespace App\Libraries\FileManager;

use Aws\CloudFront\CloudFrontClient;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Adapter\Local as LocalAdapter;
use League\Flysystem\Cached\CachedAdapter;
use Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter;

class Url
{
    /**
     * The Flysystem filesystem implementation.
     *
     * @var \League\Flysystem\FilesystemInterface
     */
    protected $driver;

    /**
     * Construct
     */
    public function __construct(FilesystemAdapter $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Get a temporary URL for the file at the given path.
     *
     * @param  string  $path
     * @param  \DateTimeInterface  $expiration
     * @param  array  $options
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getTemporaryUrl($path, $expiration, array $options = [])
    {
        $adapter = $this->driver->getAdapter();
        if ($adapter instanceof CachedAdapter) {
            $adapter = $adapter->getAdapter();
        }
        if (method_exists($adapter, 'getTemporaryUrl')) {
            return $adapter->getTemporaryUrl($path, $expiration, $options);
        } elseif ($adapter instanceof AwsS3Adapter) {
            return $this->getAwsTemporaryUrl($adapter, $path, $expiration, $options);
        } elseif ($adapter instanceof LocalAdapter) {
            return $this->getLocalTemporaryUrl($path, $expiration);
        } elseif ($adapter instanceof GoogleStorageAdapter) {
            return $this->getGcsTemporaryUrl($adapter, $path, $expiration, $options);
        } else {
            throw new RuntimeException('This driver does not support creating temporary URLs.');
        }
    }
    /**
     * Get a temporary URL for the file in local.
     *
     * @param  string $path
     * @param  \DateTimeInterface $expiration
     * @return string
     */
    public function getLocalTemporaryUrl($path, $expiration)
    {
        return url()->temporarySignedRoute('local.security_file', now()->addSeconds($expiration), [
            'filePath' => $path,
        ]);
    }
    /**
     * Get a temporary URL for the file in Google Cloud Storage.
     *
     * @param  string $path
     * @param  \DateTimeInterface $expiration
     * @param  array $options
     * @return string
     */
    public function getGcsTemporaryUrl($adapter, $path, $expiration, $options)
    {
        $object = $adapter->bucket->object($path);
        $url = $object->signedUrl(new \DateTime('+ ' . $expiration . ' seconds'), $options);

        return $url;
    }
    /**
     * Get a temporary URL for the file at the given path.
     *
     * @param  \League\Flysystem\AwsS3v3\AwsS3Adapter  $adapter
     * @param  string $path
     * @param  \DateTimeInterface $expiration
     * @param  array $options
     * @return string
     */
    public function getAwsTemporaryUrl($adapter, $path, $expiration, $options)
    {
        if (config('filesystems.disks.' . $this->storage . '.serve_via_cloudfront')) {
            return $this->getAwsCloudFrontSignedUrl($path, $expiration, $options);
        }
        return $this->getAwsS3PreSignedUrl($adapter, $path, $expiration, $options);
    }
    /**
     * Get a pre-signed URL for the file in Aws S3 bucket.
     *
     * @param  \League\Flysystem\AwsS3v3\AwsS3Adapter  $adapter
     * @param  string $path
     * @param  \DateTimeInterface $expiration
     * @param  array $options
     * @return string
     */
    public function getAwsS3PreSignedUrl($adapter, $path, $expiration, $options)
    {
        $client = $adapter->getClient();
        $command = $client->getCommand('GetObject', array_merge([
            'Bucket' => $adapter->getBucket(),
            'Key' => $adapter->getPathPrefix() . $path,
        ], $options));
        return (string) $client->createPresignedRequest(
            $command,
            $expiration
        )->getUri();
    }
    /**
     * Get a signed URL for Aws S3 via Cloudfront.
     *
     * @param  string $path
     * @param  \DateTimeInterface $expiration
     * @param  array $options
     * @return string
     */
    public function getAwsCloudFrontSignedUrl($path, $expiration, $options)
    {
        $cloudFrontConfig = config('filesystems.disks.' . $this->storage . '.cloudfront');
        $client = new CloudFrontClient([
            'version' => 'latest',
            'region' => config('filesystems.disks.' . $this->storage . '.region'),
        ]);
        $resourceUrl = $this->concatPathToUrl($cloudFrontConfig['protocol'].'://'.$cloudFrontConfig['domain'], $path);
        // Create a signed URL for the resource using the canned policy
        return (string) $client->getSignedUrl(array_merge([
            'url' => $resourceUrl,
            'expires' => time() + $expiration,
            'private_key' => base_path($cloudFrontConfig['private_key_path']),
            'key_pair_id' => $cloudFrontConfig['key_pair_id'],
        ], $options));
    }
}
