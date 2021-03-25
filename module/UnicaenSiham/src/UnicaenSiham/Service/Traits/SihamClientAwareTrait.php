<?php


namespace UnicaenSiham\Service\Traits;

use UnicaenSiham\Service\SihamClient;

trait SihamClientAwareTrait
{
    protected SihamClient $sihamClient;



    public function setSihamClient(SihamClient $service)
    {
        $this->sihamClient = $service;
    }
}