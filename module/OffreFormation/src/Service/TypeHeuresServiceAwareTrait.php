<?php

namespace OffreFormation\Service;


/**
 * Description of TypeHeuresServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeHeuresServiceAwareTrait
{
    protected ?TypeHeuresService $serviceTypeHeures = null;



    /**
     * @param TypeHeuresService $serviceTypeHeures
     *
     * @return self
     */
    public function setServiceTypeHeures(?TypeHeuresService $serviceTypeHeures)
    {
        $this->serviceTypeHeures = $serviceTypeHeures;

        return $this;
    }



    public function getServiceTypeHeures(): ?TypeHeuresService
    {
        if (empty($this->serviceTypeHeures)) {
            $this->serviceTypeHeures = \Application::$container->get(TypeHeuresService::class);
        }

        return $this->serviceTypeHeures;
    }
}