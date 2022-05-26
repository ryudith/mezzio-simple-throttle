<?php
/**
 * @author Ryudith
 * @license Apache-2.0
 * @package Ryudith\MezzioSimpleThrottle\Storage
 */

declare(strict_types=1);

namespace Ryudith\MezzioSimpleThrottle\Storage;

interface StorageInterface
{
    /**
     * Save record data.
     * 
     * @param string $key Throttle record data key for save.
     * @param array $data Assoc array throttle record data.
     * @return bool Return true if success or false if fail.
     */
    public function save (string $key, array $data) : bool;

    /**
     * Load record data as assoc array.
     * 
     * @param string $key Throttle record data key to load.
     * @return array|null Return assoc array throttle record data or null if no data.
     */
    public function load (string $key) : array|null;
}