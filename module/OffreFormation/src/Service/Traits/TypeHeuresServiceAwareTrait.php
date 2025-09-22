<?php

namespace OffreFormation\Service\Traits;

use OffreFormation\Service\TypeHeuresService;

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
            $this->serviceTypeHeures = \Framework\Application\Application::getInstance()->container()->get(TypeHeuresService::class);
        }

        return $this->serviceTypeHeures;
    }
}