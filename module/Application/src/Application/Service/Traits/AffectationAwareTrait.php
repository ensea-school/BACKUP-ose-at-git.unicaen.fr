<?php

namespace Application\Service\Traits;

use Application\Service\Affectation;

/**
 * Description of AffectationAwareTrait
 *
 * @author UnicaenCode
 */
trait AffectationAwareTrait
{
    /**
     * @var Affectation
     */
    private $serviceAffectation;



    /**
     * @param Affectation $serviceAffectation
     *
     * @return self
     */
    public function setServiceAffectation(Affectation $serviceAffectation)
    {
        $this->serviceAffectation = $serviceAffectation;

        return $this;
    }



    /**
     * @return Affectation
     */
    public function getServiceAffectation()
    {
        if (empty($this->serviceAffectation)) {
            $this->serviceAffectation = \Application::$container->get('ApplicationAffectation');
        }

        return $this->serviceAffectation;
    }
}