<?php
/**
 * Implementation ThrottleDataInterface for file based storage to save throttle data.
 * Array data key :
 * - 'hit'      : How many request hit
 * - 'last_hit' : unixtimestamp last request
 * - 'ip'       : Request IP
 * - 'uri_path' : Request URI path
 * 
 * @author Ryudith
 * @license Apache-2.0
 * @package Ryudith\MezzioSimpleThrottle\Storage
 */

declare(strict_types=1);

namespace Ryudith\MezzioSimpleThrottle\Storage;

class FileSystemThrottleStorage implements StorageInterface
{
    /**
     * Absolute or relative path location to save throttle record data.
     * 
     * @var string $fileDataPath
     */
    private string $fileDataPath;

    /**
     * Delimiter throttle data inside file.
     * 
     * @var string $fileDataDelimiter
     */
    private string $fileDataDelimiter;

    /**
     * Set $fileDataPath from $path value, 
     * then check location if not exist create the location directory.
     * 
     * @param string $path Path location for save file.
     * @param string $fileDataDelimiter Data delimiter inside file.
     */
    public function __construct (string $path, string $fileDataDelimiter)
    {
        $this->fileDataDelimiter = $fileDataDelimiter;
        $this->fileDataPath = $path;
        if (! file_exists($this->fileDataPath)) 
        {
            mkdir($this->fileDataPath, 0755, true);
        }
    }

    /**
     * Convert data from assoc array to string(implode) then save to file with name $key.
     * 
     * @param string $key Key file to save $data.
     * @param array $data Assoc array throttle data.
     * @return bool If success return true else false.
     */
    public function save (string $key, array $data) : bool 
    {
        $filePath = $this->fileDataPath.'/'.$key;
        $fileData = $data['hit'].$this->fileDataDelimiter.
            $data['last_hit'].$this->fileDataDelimiter.
            $data['ip'].$this->fileDataDelimiter.
            $data['uri_path'];

        return (bool) file_put_contents($filePath, $fileData);
    }

    /**
     * Load string data from file and convert it to assoc array data.
     * 
     * @param string $key File name data.
     * @return ?array Assoc array throttle data or null if no data.
     */
    public function load (string $key) : ?array 
    {
        $filePath = $this->fileDataPath.'/'.$key;
        $fileData = null;
        if (! file_exists($filePath)) 
        {
            return $fileData;
        }

        $fileData = file_get_contents($filePath);
        $tmpData = explode($this->fileDataDelimiter, $fileData);
        if (count($tmpData) < 4)
        {
            return null;
        }
        return [
            'hit' => (int) $tmpData[0],
            'last_hit' => (int) $tmpData[1],
            'ip' => $tmpData[2],
            'uri_path' => $tmpData[3],
        ];
    }
}