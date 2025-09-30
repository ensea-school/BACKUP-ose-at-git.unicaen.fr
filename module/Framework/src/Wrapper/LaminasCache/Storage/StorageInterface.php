<?php

namespace Laminas\Cache\Storage;

use Traversable;

interface StorageInterface
{
    /**
     * Set options.
     *
     * @param array|Traversable|Adapter\AdapterOptions $options
     * @return StorageInterface Fluent interface
     */
    public function setOptions($options);

    /**
     * Get options
     *
     * @return Adapter\AdapterOptions
     */
    public function getOptions();

    /* reading */
    /**
     * Get an item.
     *
     * @param  string  $key
     * @param  bool $success
     * @return mixed Data on success, null on failure
     */
    public function getItem($key, &$success = null, mixed &$casToken = null);

    /**
     * Get multiple items.
     *
     * @return array Associative array of keys and values
     */
    public function getItems(array $keys);

    /**
     * Test if an item exists.
     *
     * @param  string $key
     * @return bool
     */
    public function hasItem($key);

    /**
     * Test multiple items.
     *
     * @return array Array of found keys
     */
    public function hasItems(array $keys);

    /**
     * Get metadata of an item.
     *
     * @param  string $key
     * @return array|bool Metadata on success, false on failure
     */
    public function getMetadata($key);

    /**
     * Get multiple metadata
     *
     * @return array Associative array of keys and metadata
     */
    public function getMetadatas(array $keys);

    /* writing */
    /**
     * Store an item.
     *
     * @param  string $key
     * @return bool
     */
    public function setItem($key, mixed $value);

    /**
     * Store multiple items.
     *
     * @return array Array of not stored keys
     */
    public function setItems(array $keyValuePairs);

    /**
     * Add an item.
     *
     * @param  string $key
     * @return bool
     */
    public function addItem($key, mixed $value);

    /**
     * Add multiple items.
     *
     * @return array Array of not stored keys
     */
    public function addItems(array $keyValuePairs);

    /**
     * Replace an existing item.
     *
     * @param  string $key
     * @return bool
     */
    public function replaceItem($key, mixed $value);

    /**
     * Replace multiple existing items.
     *
     * @return array Array of not stored keys
     */
    public function replaceItems(array $keyValuePairs);

    /**
     * Set an item only if token matches
     *
     * It uses the token received from getItem() to check if the item has
     * changed before overwriting it.
     *
     * @see    getItem()
     * @see    setItem()
     *
     * @param  string $key
     * @return bool
     */
    public function checkAndSetItem(mixed $token, $key, mixed $value);

    /**
     * Reset lifetime of an item
     *
     * @param  string $key
     * @return bool
     */
    public function touchItem($key);

    /**
     * Reset lifetime of multiple items.
     *
     * @return array Array of not updated keys
     */
    public function touchItems(array $keys);

    /**
     * Remove an item.
     *
     * @param  string $key
     * @return bool
     */
    public function removeItem($key);

    /**
     * Remove multiple items.
     *
     * @return array Array of not removed keys
     */
    public function removeItems(array $keys);

    /**
     * Increment an item.
     *
     * @param  string $key
     * @param  int    $value
     * @return int|bool The new value on success, false on failure
     */
    public function incrementItem($key, $value);

    /**
     * Increment multiple items.
     *
     * @return array Associative array of keys and new values
     */
    public function incrementItems(array $keyValuePairs);

    /**
     * Decrement an item.
     *
     * @param  string $key
     * @param  int    $value
     * @return int|bool The new value on success, false on failure
     */
    public function decrementItem($key, $value);

    /**
     * Decrement multiple items.
     *
     * @return array Associative array of keys and new values
     */
    public function decrementItems(array $keyValuePairs);

    /* status */

    /**
     * Capabilities of this storage
     *
     */
    public function getCapabilities();
}
