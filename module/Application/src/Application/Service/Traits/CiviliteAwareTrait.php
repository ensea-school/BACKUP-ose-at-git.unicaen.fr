<?php

namespace Application\Service\Traits;

use Application\Service\Civilite;

/**
 * Description of CiviliteAwareTrait
 *
 * @author UnicaenCode
 */
trait CiviliteAwareTrait
{
    /**
     * @var Civilite
     */
    private $serviceCivilite;



    /**
     * @param Civilite $serviceCivilite
     *
     * @return self
     */
    public function setServiceCivilite(Civilite $serviceCivilite)
    {
        $this->serviceCivilite = $serviceCivilite;

        return $this;
    }



    /**
     * @return Civilite
     */
    public function getServiceCivilite()
    {
        if (empty($this->serviceCivilite)) {
            $this->serviceCivilite = \Application::$container->get('ApplicationCivilite');
        }

        return $this->serviceCivilite;
    }
}