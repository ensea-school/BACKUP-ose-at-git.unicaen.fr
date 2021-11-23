<?php

namespace Application\Service\Traits;

use Application\Service\IntervenantDossierService;

/**
 * Description of IntervenantDossierAwareTrait
 *
 * @author UnicaenCode
 */
trait IntervenantDossierServiceAwareTrait
{
    /**
     * @var IntervenantDossierService
     */
    private $serviceIntervenantDossier;



    /**
     * @param IntervenantDossierService $serviceIntervenantDossier
     *
     * @return self
     */
    public function setServiceIntervenantDossier(IntervenantDossierService $serviceIntervenantDossier)
    {
        $this->serviceIntervenantDossier = $serviceIntervenantDossier;

        return $this;
    }



    /**
     * @return IntervenantDossierService
     */
    public function getServiceIntervenantDossier()
    {
        if (empty($this->serviceIntervenantDossier)) {
            $this->serviceIntervenantDossier = \Application::$container->get(IntervenantDossierService::class);
        }

        return $this->serviceIntervenantDossier;
    }
}