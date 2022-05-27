<?php
/**
 * Factory class to create FileThrottleData.
 * 
 * @author Ryudith
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
     * @param ContainerInterface $container Implementation object of ContainerInterface
     * @return FileSystemThrottleStorage
     */
    public function __invoke (ContainerInterface $container) : FileSystemThrottleStorage
    {
        $config = $container->get('config')['mezzio_simple_throttle'];
        return new FileSystemThrottleStorage($config['throttle_data_dir'], $config['file_data_delimiter']);
    }
}