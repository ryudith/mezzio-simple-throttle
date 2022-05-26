<?php
/**
 * @author Ryudith
 * @license Apache-2.0
 * @package Ryudith\MezzioSimpleThrottle\Response
 */
declare(strict_types=1);

namespace Ryudith\MezzioSimpleThrottle\Response;

use Psr\Http\Message\ResponseInterface;

interface ThrottleResponseInterface
{
    /**
     * Generate the response.
     * 
     * @return Psr\Http\Message\ResponseInterface
     */
    public function generateResponse() : ResponseInterface;
}