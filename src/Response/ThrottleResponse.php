<?php
/**
 * Implementation for ThrottleResponseInterface, 
 * which responsible for give response after throttle limit hit.
 * 
 * @author Ryudith
 * @license Apache-2.0
 * @package Ryudith\MezzioSimpleThrottle\Response
 */

declare(strict_types=1);

namespace Ryudith\MezzioSimpleThrottle\Response;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;

class ThrottleResponse implements ThrottleResponseInterface
{
    /**
     * Flag if the request is JSON request.
     * 
     * @var bool $isJsonRequest
     */
    private bool $isJsonRequest = false;

    /**
     * Initialize by set $isJsonRequest flag true or false depend request header 'content-type'.
     * 
     * @param Psr\Http\Message\ServerRequestInterface $request To get header 'content-type' for flag $isJsonRequest.
     */
    public function __construct (ServerRequestInterface $request)
    {
        if (1 === preg_match('#^application/(|[\S]+\+)json($|[ ;])#', $request->getHeaderLine('content-type'))) 
        {
            $this->isJsonRequest = true;
        }
    }

    /**
     * Return implementation ResponseInterface depend $isJsonRequest flag.
     * 
     * @return Psr\Http\Message\ResponseInterface Response object.
     */
    public function generateResponse () : ResponseInterface
    {
        if ($this->isJsonRequest) 
        {
            return new JsonResponse(['status' => 'error', 'message' => 'Request limit'], 429);
        }

        return new HtmlResponse('<p style="margin: 0 auto;">Request Limit</p>', 429);
    }
}