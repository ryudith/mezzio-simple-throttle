<?php
/**
 * @author Ryudith
 * @package Ryudith\MezzioSimpleThrottle\Response
 */

declare(strict_types=1);

namespace Ryudith\MezzioSimpleThrottle\Response;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

class ThrottleResponseFactory
{
    /**
     * Create object ThrottleLimitResponse by passing Psr\Container\ContainerInterface implementation object.
     * 
     * @param ContainerInterface $container Implementation object of ContainerInterface.
     * @return ThrottleResponse Object to give response.
     */
    public function __invoke(ContainerInterface $container) : ThrottleResponse 
    {
        $serverRequest = $container->get(ServerRequestInterface::class)();

        return new ThrottleResponse($serverRequest);
    }
}