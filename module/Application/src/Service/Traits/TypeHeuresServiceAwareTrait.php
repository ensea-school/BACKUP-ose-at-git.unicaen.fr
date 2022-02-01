<?php

namespace Application\Service\Traits;

use Application\Service\TypeHeuresService;

/**
 * Description of TypeHeuresServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeHeuresServiceAwareTrait
{
    protected ?TypeHeuresService $serviceTypeHeures;



    /**
     * @param TypeHeuresService|null $serviceTypeHeures
     *
     * @return self
     */
    public function setServiceTypeHeures( ?TypeHeuresService $serviceTypeHeures )
    {
        $this->serviceTypeHeures = $serviceTypeHeures;

        return $this;
    }



    public function getServiceTypeHeures(): ?TypeHeuresService
    {
        if (!$this->serviceTypeHeures){
            $this->serviceTypeHeures = \Application::$container->get(TypeHeuresService::class);
        }

        return $this->serviceTypeHeures;
    }
}