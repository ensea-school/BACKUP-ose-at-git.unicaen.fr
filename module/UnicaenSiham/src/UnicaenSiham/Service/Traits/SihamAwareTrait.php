<?php


namespace UnicaenSiham\Service\Traits;


use UnicaenSiham\Service\Siham;

trait SihamAwareTrait
{
    protected Siham $siham;



    public function setSiham(Siham $service)
    {
        $this->siham = $service;
    }

}