<?php

namespace ExportRH\Connecteur\Siham;

use ExportRH\Connecteur\ExportRhInterface;

class SihamConnecteur implements ExportRhInterface
{
    /**
     * @var array
     */
    private $config = [];



    public function __construct(array $config)
    {
        $this->config = $config;
    }



    public function connect(): ExportRhInterface
    {
        // TODO: Implement connect() method.
    }



    public function disconnect(): ExportRhInterface
    {
        // TODO: Implement disconnect() method.
    }



    public function test()
    {
        echo 'test réussi';
        var_dump($this->config);
    }
}