<?php
/**
 * Factory to create Ryudith\MezzioSimpleThrottle\SimpleThrottle.
 * 
 * @author Ryudith
 * @package Ryudith\MezzioSimpleThrottle
 */
declare(strict_types=1);

namespace Ryudith\MezzioSimpleThrottle;

use Psr\Container\ContainerInterface;

class SimpleThrottleFactory 
{
    /**
     * Create object SimpleThrottle by passing config 'mezzio_simple_throttle' from global config,
     * Ryudith\MezzioSimpleThrottle\Storage\StorageInterface implementation object, 
     * and Ryudith\MezzioSimpleThrottle\ThrottleResponseInterface implementation object.
     * 
     * @param ContainerInterface $container Implementation object of ContainerInterface.
     * @return SimpleThrottle
     */
    public function __invoke(ContainerInterface $container) : SimpleThrottle
    {
        $conf = $container->get('config')['mezzio_simple_throttle'];
        $throttleData = $container->get($conf['throttle_storage_class']);
        $throttleResponse = $container->get($conf['throttle_response_class']);
        
        return new SimpleThrottle($conf, $throttleData, $throttleResponse);
    }
}