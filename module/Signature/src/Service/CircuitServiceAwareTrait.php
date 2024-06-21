<?php

namespace Signature\Service;


/**
 * Description of CircuitServiceAwareTrait
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
trait CircuitServiceAwareTrait
{
    protected ?CircuitService $serviceCircuit = null;



    /**
     * @param CircuitService $serviceCircuit
     *
     * @return self
     */
    public function setServiceCircuit(?CircuitService $serviceCircuit): void
    {
        $this->serviceCircuit = $serviceCircuit;
    }



    public function getServiceCircuit(): ?CircuitService
    {
        return $this->serviceCircuit;
    }
}