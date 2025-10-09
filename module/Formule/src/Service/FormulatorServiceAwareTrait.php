<?php

namespace Formule\Service;


/**
 * Description of FormulatorServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait FormulatorServiceAwareTrait
{
    protected ?FormulatorService $serviceFormulator = null;



    /**
     * @param FormulatorService $serviceFormulator
     *
     * @return self
     */
    public function setServiceFormulator(?FormulatorService $serviceFormulator)
    {
        $this->serviceFormulator = $serviceFormulator;

        return $this;
    }



    public function getServiceFormulator(): ?FormulatorService
    {
        if (empty($this->serviceFormulator)) {
            $this->serviceFormulator =\Unicaen\Framework\Application\Application::getInstance()->container()->get(FormulatorService::class);
        }

        return $this->serviceFormulator;
    }
}