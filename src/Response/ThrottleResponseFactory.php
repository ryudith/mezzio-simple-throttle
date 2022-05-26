<?php
/**
 * @author Ryudith
 * @license Apache-2.0
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
     * @param Psr\Container\ContainerInterface $container Implementation object of ContainerInterface.
     * @return Ryudith\MezzioSimpleThrottle\ThrottleResponse Object to give response.
     */
    public function __invoke(ContainerInterface $container) : ThrottleResponse 
    {
        $serverRequest = $container->get(ServerRequestInterface::class)();

        return new ThrottleResponse($serverRequest);
    }
}