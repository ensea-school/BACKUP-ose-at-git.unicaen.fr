<?php

namespace Paiement\Service;


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
            $this->serviceMiseEnPaiementIntervenantStructure = \Framework\Application\Application::getInstance()->container()->get(MiseEnPaiementIntervenantStructureService::class);
        }

        return $this->serviceMiseEnPaiementIntervenantStructure;
    }
}