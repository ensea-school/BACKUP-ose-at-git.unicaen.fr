<?php

namespace Application\Cache;

class CacheContainer {

    /**
     * @var CacheService
     */
    private $cacheService;

    /**
     * @var string
     */
    private $class;



    public function __construct(CacheService $cacheService, $class)
    {
        $this->cacheService = $cacheService;
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
    public function __get($name)
    {
        return $this->cacheService->get($this->class, $name);
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
        $this->cacheService->remove($this->class, $name);
    }

}