<?php

namespace Application\Service\Traits;

use Application\Service\TypeHeures;

/**
 * Description of TypeHeuresAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeHeuresAwareTrait
{
    /**
     * @var TypeHeures
     */
    private $serviceTypeHeures;



    /**
     * @param TypeHeures $serviceTypeHeures
     *
     * @return self
     */
    public function setServiceTypeHeures(TypeHeures $serviceTypeHeures)
    {
        $this->serviceTypeHeures = $serviceTypeHeures;

        return $this;
    }



    /**
     * @return TypeHeures
     */
    public function getServiceTypeHeures()
    {
        if (empty($this->serviceTypeHeures)) {
            $this->serviceTypeHeures = \Application::$container->get('ApplicationTypeHeures');
        }

        return $this->serviceTypeHeures;
    }
}