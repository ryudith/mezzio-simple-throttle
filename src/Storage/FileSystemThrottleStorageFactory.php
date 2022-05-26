<?php
/**
 * Factory class to create FileThrottleData.
 * 
 * @author Ryudith
 * @license Apache-2.0
 * @package Ryudith\MezzioSimpleThrottle\Storage
 */

declare(strict_types=1);

namespace Ryudith\MezzioSimpleThrottle\Storage;

use Psr\Container\ContainerInterface;

class FileSystemThrottleStorageFactory
{
    /**
     * Create FileThrottleData by pass 'throttle_file_data_dir' and 'throttle_file_data_delimiter' 
     * from global config object.
     * 
     * @param Psr\Container\ContainerInterface $container Implementation object of ContainerInterface
     * @return Ryudith\MezzioSimpleThrottle\StorageData\FileThrottleData
     */
    public function __invoke (ContainerInterface $container) : FileSystemThrottleStorage
    {
        $config = $container->get('config')['mezzio_simple_throttle'];
        return new FileSystemThrottleStorage($config['throttle_data_dir'], $config['file_data_delimiter']);
    }
}