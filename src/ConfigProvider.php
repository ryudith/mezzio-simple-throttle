<?php
/**
 * All configuration for library.
 * 
 * @author Ryudith
 * @package Ryudith\MezzioSimpleThrottle
 */

declare(strict_types=1);

namespace Ryudith\MezzioSimpleThrottle;

use Ryudith\MezzioSimpleThrottle\Response\ThrottleResponse;
use Ryudith\MezzioSimpleThrottle\Response\ThrottleResponseFactory;
use Ryudith\MezzioSimpleThrottle\Storage\FileSystemThrottleStorage;
use Ryudith\MezzioSimpleThrottle\Storage\FileSystemThrottleStorageFactory;

class ConfigProvider
{
    /**
     * Compose array to merge with global config.
     * 
     * @return array Assoc array to merge with global assoc array config.
     */
    public function __invoke () : array
    {
        return [
            'dependencies' => [
                'factories' => [
                    FileSystemThrottleStorage::class => FileSystemThrottleStorageFactory::class,
                    ThrottleResponse::class => ThrottleResponseFactory::class,
                    SimpleThrottle::class => SimpleThrottleFactory::class,
                ],
            ],
            'mezzio_simple_throttle' => [
                'request_limit_per_minute' => 10,
                'request_real_ip_key' => 'REMOTE_ADDR',  // key for $_ENV or $_SERVER to get request real ip
                'ip_path_key' => true,  // data key based IP and URI path or IP only data key
                'throttle_data_dir' => './data/throttle',
                'file_data_delimiter' => '||',
                'throttle_storage_class' => FileSystemThrottleStorage::class,
                'throttle_response_class' => ThrottleResponse::class,
            ],
        ];
    }
}