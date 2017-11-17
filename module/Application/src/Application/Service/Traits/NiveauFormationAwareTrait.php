<?php

namespace Application\Service\Traits;

use Application\Service\NiveauFormation;

/**
 * Description of NiveauFormationAwareTrait
 *
 * @author UnicaenCode
 */
trait NiveauFormationAwareTrait
{
    /**
     * @var NiveauFormation
     */
    private $serviceNiveauFormation;



    /**
     * @param NiveauFormation $serviceNiveauFormation
     *
     * @return self
     */
    public function setServiceNiveauFormation(NiveauFormation $serviceNiveauFormation)
    {
        $this->serviceNiveauFormation = $serviceNiveauFormation;

        return $this;
    }



    /**
     * @return NiveauFormation
     */
    public function getServiceNiveauFormation()
    {
        if (empty($this->serviceNiveauFormation)) {
            $this->serviceNiveauFormation = \Application::$container->get('ApplicationNiveauFormation');
        }

        return $this->serviceNiveauFormation;
    }
}