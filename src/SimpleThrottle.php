<?php
/**
 * Real class that do functionality for check and limit throttle request.
 * 
 * @author Ryudith
 * @package Ryudith\MezzioSimpleThrottle
 */
declare(strict_types=1);

namespace Ryudith\MezzioSimpleThrottle;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ryudith\MezzioSimpleThrottle\Response\ThrottleResponseInterface;
use Ryudith\MezzioSimpleThrottle\Storage\StorageInterface;

class SimpleThrottle implements MiddlewareInterface
{
    /**
     * Assoc array that contain config.
     * 
     * @var array $config
     */
    private array $config;

    /**
     * StorageInterface object reference.
     * 
     * @var StorageInterface $throttleData
     */
    private StorageInterface $throttleData;

    /**
     * ThrottleResponseInterface object reference.
     * 
     * @var ThrottleResponseInterface $throttleResponse
     */
    private ThrottleResponseInterface $throttleResponse;

    /**
     * Assign class properties.
     * 
     * @param array $config 'mezzio_simple_throttle' assoc array config.
     * @param StorageInterface $throttleData Implementation object StorageInterface.
     * @param ThrottleResponseInterface $throttleResponse Implementation object ThrottleResponseInterface.
     */
    public function __construct (
        array $config, 
        StorageInterface $throttleData, 
        ThrottleResponseInterface $throttleResponse
    ) {
        $this->config = $config;
        $this->throttleData = $throttleData;
        $this->throttleResponse = $throttleResponse;
    }

    /**
     * Generate data key, check throttle data, create or update throttle data and 
     * give response(message limit) if throttle limit hit by request.
     * 
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handle
     * @return ResponseInterface
     */
    public function process (ServerRequestInterface $request, RequestHandlerInterface $handle) : ResponseInterface
    {
        $rawKey = $requestIp = $this->getRequestIP();
        $uriPath = $request->getUri()->getPath();
        if ($this->config['ip_path_key']) 
        {
            $rawKey .= '->'.$uriPath;
        }

        $dataKey = md5($rawKey);
        $recordData = $this->throttleData->load($dataKey);
        if ($recordData === null) 
        {
            $recordData = [
                'hit' => 1,
                'last_hit' => time(),
                'ip' => $requestIp,
                'uri_path' => $uriPath,
            ];
            $this->throttleData->save($dataKey, $recordData);

            return $handle->handle($request);
        }
        
        $diffTime = time() - $recordData['last_hit'];
        if ($diffTime < 60 && $recordData['hit'] > $this->config['request_limit_per_minute']) 
        {
            return $this->throttleResponse->generateResponse();
        }

        $recordData['last_hit'] = time();
        if ($recordData['hit'] > $this->config['request_limit_per_minute'])
        {
            $recordData['hit'] = 1;
        }
        else 
        {
            $recordData['hit'] += 1;
        }
        $this->throttleData->save($dataKey, $recordData);
        
        return $handle->handle($request);
    }

    /**
     * Extract IP from string (possible IP with PORT).
     * 
     * @return string String IP from request.
     */
    private function getRequestIP () : string
    {
        $realIpKey = $this->config['request_real_ip_key'];
        $realIp = getenv($realIpKey);
        if ($realIp === false && ! isset($_SERVER[$realIpKey])) 
        {
            // give default value if request has no IP
            return '0.0.0.0';
        }
        else if ($realIp === false) 
        {
            $realIp = $_SERVER[$realIpKey];
        }

        $ip = explode(']:', $realIp);
        if (count($ip) > 1) 
        {
            return trim($ip[0], '[');
        }

        $ip = explode(':', $realIp);
        return $ip[0];
    }
}