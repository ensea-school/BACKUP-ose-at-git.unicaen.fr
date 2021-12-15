<?php

namespace Application\Cache;

class CacheContainer
{

    private CacheService $cacheService;

    private              $object     = null;

    private string       $class;

    private array        $localCache = [];



    public function __construct(CacheService $cacheService, $class)
    {
        $this->cacheService = $cacheService;
        if (is_object($class)) $this->object = $class;
        $this->class = is_object($class) ? get_class($class) : $class;
    }



    /**
     * is utilized for reading data from inaccessible members.
     *
     * @param $name string
     *
     * @return mixed
     * @link https://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
     */
    public function __get(string $name)
    {
        if (!array_key_exists($name, $this->localCache)) {
            $this->localCache[$name] = $this->cacheService->get($this->class, $name);
        }

        return $this->localCache[$name];
    }



    /**
     * run when writing data to inaccessible members.
     *
     * @param $name  string
     * @param $value mixed
     *
     * @return void
     * @link https://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
     */
    public function __set($name, $value)
    {
        $this->localCache[$name] = $value;
        $this->cacheService->set($this->class, $name, $value);
    }



    /**
     * is triggered by calling isset() or empty() on inaccessible members.
     *
     * @param $name string
     *
     * @return bool
     * @link https://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
     */
    public function __isset($name)
    {
        if (array_key_exists($name, $this->localCache)) {
            return true;
        }

        return $this->cacheService->exists($this->class, $name);
    }



    /**
     * is invoked when unset() is used on inaccessible members.
     *
     * @param $name string
     *
     * @return void
     * @link https://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
     */
    public function __unset($name)
    {
        unset($this->localCache[$name]);
        $this->cacheService->remove($this->class, $name);
    }



    public function __call(string $name, $arguments)
    {
        if ($this->__isset($name)) {
            return $this->__get($name);
        } else {
            $callable = $arguments[0];
            if (is_string($callable) && $this->object && method_exists($this->object, $callable)) {
                $value = $this->object->$callable();
            } else {
                $value = $callable();
            }
            $this->__set($name, $value);

            return $value;
        }
    }
}