<?php

namespace Application\Service\Traits;

use Application\Service\Dossier;

/**
 * Description of DossierAwareTrait
 *
 * @author UnicaenCode
 */
trait DossierAwareTrait
{
    /**
     * @var Dossier
     */
    private $serviceDossier;



    /**
     * @param Dossier $serviceDossier
     *
     * @return self
     */
    public function setServiceDossier(Dossier $serviceDossier)
    {
        $this->serviceDossier = $serviceDossier;

        return $this;
    }



    /**
     * @return Dossier
     */
    public function getServiceDossier()
    {
        if (empty($this->serviceDossier)) {
            $this->serviceDossier = \Application::$container->get('ApplicationDossier');
        }

        return $this->serviceDossier;
    }
}