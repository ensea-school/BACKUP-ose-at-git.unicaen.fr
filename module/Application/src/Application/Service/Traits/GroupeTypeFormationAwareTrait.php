<?php

namespace Application\Service\Traits;

use Application\Service\GroupeTypeFormation;

/**
 * Description of GroupeTypeFormationAwareTrait
 *
 * @author UnicaenCode
 */
trait GroupeTypeFormationAwareTrait
{
    /**
     * @var GroupeTypeFormation
     */
    private $serviceGroupeTypeFormation;



    /**
     * @param GroupeTypeFormation $serviceGroupeTypeFormation
     *
     * @return self
     */
    public function setServiceGroupeTypeFormation(GroupeTypeFormation $serviceGroupeTypeFormation)
    {
        $this->serviceGroupeTypeFormation = $serviceGroupeTypeFormation;

        return $this;
    }



    /**
     * @return GroupeTypeFormation
     */
    public function getServiceGroupeTypeFormation()
    {
        if (empty($this->serviceGroupeTypeFormation)) {
            $this->serviceGroupeTypeFormation = \Application::$container->get('ApplicationGroupeTypeFormation');
        }

        return $this->serviceGroupeTypeFormation;
    }
}