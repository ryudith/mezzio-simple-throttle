<?php
/**
 * @author Ryudith
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
     * @return ResponseInterface
     */
    public function generateResponse() : ResponseInterface;
}