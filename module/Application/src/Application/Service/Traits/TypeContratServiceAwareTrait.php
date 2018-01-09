<?php

namespace Application\Service\Traits;

use Application\Service\TypeContratService;

/**
 * Description of TypeContratAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeContratServiceAwareTrait
{
    /**
     * @var TypeContratService
     */
    private $serviceTypeContrat;



    /**
     * @param TypeContratService $serviceTypeContrat
     *
     * @return self
     */
    public function setServiceTypeContrat(TypeContratService $serviceTypeContrat)
    {
        $this->serviceTypeContrat = $serviceTypeContrat;

        return $this;
    }



    /**
     * @return TypeContratService
     */
    public function getServiceTypeContrat()
    {
        if (empty($this->serviceTypeContrat)) {
            $this->serviceTypeContrat = \Application::$container->get(TypeContratService::class);
        }

        return $this->serviceTypeContrat;
    }
}