<?php

namespace Application\Service\Traits;

use Application\Service\MiseEnPaiementIntervenantStructureService;

/**
 * Description of MiseEnPaiementIntervenantStructureServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait MiseEnPaiementIntervenantStructureServiceAwareTrait
{
    protected ?MiseEnPaiementIntervenantStructureService $serviceMiseEnPaiementIntervenantStructure = null;



    /**
     * @param MiseEnPaiementIntervenantStructureService $serviceMiseEnPaiementIntervenantStructure
     *
     * @return self
     */
    public function setServiceMiseEnPaiementIntervenantStructure(?MiseEnPaiementIntervenantStructureService $serviceMiseEnPaiementIntervenantStructure)
    {
        $this->serviceMiseEnPaiementIntervenantStructure = $serviceMiseEnPaiementIntervenantStructure;

        return $this;
    }



    public function getServiceMiseEnPaiementIntervenantStructure(): ?MiseEnPaiementIntervenantStructureService
    {
        if (empty($this->serviceMiseEnPaiementIntervenantStructure)) {
            $this->serviceMiseEnPaiementIntervenantStructure = \Application::$container->get(MiseEnPaiementIntervenantStructureService::class);
        }

        return $this->serviceMiseEnPaiementIntervenantStructure;
    }
}