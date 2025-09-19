<?php

namespace Administration\Service;

/**
 * Description of ParametresServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait ParametresServiceAwareTrait
{
    protected ?ParametresService $serviceParametres = null;



    /**
     * @param ParametresService $serviceParametres
     *
     * @return self
     */
    public function setServiceParametres(?ParametresService $serviceParametres)
    {
        $this->serviceParametres = $serviceParametres;

        return $this;
    }



    public function getServiceParametres(): ?ParametresService
    {
        if (empty($this->serviceParametres)) {
            $this->serviceParametres = \Framework\Application\Application::getInstance()->container()->get(ParametresService::class);
        }

        return $this->serviceParametres;
    }
}