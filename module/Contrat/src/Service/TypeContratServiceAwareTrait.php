<?php

namespace Contrat\Service;

/**
 * Description of TypeContratServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeContratServiceAwareTrait
{
    protected ?TypeContratService $serviceTypeContrat = null;



    /**
     * @param TypeContratService $serviceTypeContrat
     *
     * @return self
     */
    public function setServiceTypeContrat(?TypeContratService $serviceTypeContrat)
    {
        $this->serviceTypeContrat = $serviceTypeContrat;

        return $this;
    }



    public function getServiceTypeContrat(): ?TypeContratService
    {
        if (empty($this->serviceTypeContrat)) {
            $this->serviceTypeContrat = \Application::$container->get(TypeContratService::class);
        }

        return $this->serviceTypeContrat;
    }
}