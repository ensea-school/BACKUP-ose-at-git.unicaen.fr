<?php

namespace Application\Service\Traits;

use Application\Service\TypeContrat;

/**
 * Description of TypeContratAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeContratAwareTrait
{
    /**
     * @var TypeContrat
     */
    private $serviceTypeContrat;



    /**
     * @param TypeContrat $serviceTypeContrat
     *
     * @return self
     */
    public function setServiceTypeContrat(TypeContrat $serviceTypeContrat)
    {
        $this->serviceTypeContrat = $serviceTypeContrat;

        return $this;
    }



    /**
     * @return TypeContrat
     */
    public function getServiceTypeContrat()
    {
        if (empty($this->serviceTypeContrat)) {
            $this->serviceTypeContrat = \Application::$container->get('ApplicationTypeContrat');
        }

        return $this->serviceTypeContrat;
    }
}