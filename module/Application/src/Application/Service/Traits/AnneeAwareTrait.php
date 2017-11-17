<?php

namespace Application\Service\Traits;

use Application\Service\Annee;

/**
 * Description of AnneeAwareTrait
 *
 * @author UnicaenCode
 */
trait AnneeAwareTrait
{
    /**
     * @var Annee
     */
    private $serviceAnnee;



    /**
     * @param Annee $serviceAnnee
     *
     * @return self
     */
    public function setServiceAnnee(Annee $serviceAnnee)
    {
        $this->serviceAnnee = $serviceAnnee;

        return $this;
    }



    /**
     * @return Annee
     */
    public function getServiceAnnee()
    {
        if (empty($this->serviceAnnee)) {
            $this->serviceAnnee = \Application::$container->get('ApplicationAnnee');
        }

        return $this->serviceAnnee;
    }
}