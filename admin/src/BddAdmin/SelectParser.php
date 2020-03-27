<?php

namespace BddAdmin;

use BddAdmin\Driver\DriverInterface;

class SelectParser
{
    /**
     * @var DriverInterface
     */
    protected $driver;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var mixed
     */
    protected $statement;



    public function __construct(DriverInterface $driver, array $options, $statement)
    {
        $this->driver    = $driver;
        $this->options   = $options;
        $this->statement = $statement;
    }



    /**
     * @return array|null
     */
    public function next(): ?array
    {
        $res = $this->driver->fetch($this->statement, $this->options);

        return $res ?: null;
    }
}