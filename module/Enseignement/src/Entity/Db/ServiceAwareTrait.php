<?php

namespace Enseignement\Entity\Db;

/**
 * Description of ServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait ServiceAwareTrait
{
    protected ?Service $service = null;



    /**
     * @param Service $service
     *
     * @return self
     */
    public function setService(?Service $service)
    {
        $this->service = $service;

        return $this;
    }



    public function getService(): ?Service
    {
        return $this->service;
    }
}