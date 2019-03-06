<?php

namespace Application\Service\Traits;

use Application\Service\TypeHeuresService;

/**
 * Description of TypeHeuresAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeHeuresServiceAwareTrait
{
    /**
     * @var TypeHeuresService
     */
    private $serviceTypeHeures;



    /**
     * @param TypeHeuresService $serviceTypeHeures
     *
     * @return self
     */
    public function setServiceTypeHeures(TypeHeuresService $serviceTypeHeures)
    {
        $this->serviceTypeHeures = $serviceTypeHeures;

        return $this;
    }



    /**
     * @return TypeHeuresService
     */
    public function getServiceTypeHeures()
    {
        if (empty($this->serviceTypeHeures)) {
            $this->serviceTypeHeures = \Application::$container->get(TypeHeuresService::class);
        }

        return $this->serviceTypeHeures;
    }
}