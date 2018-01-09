<?php

namespace Application\Service\Traits;

use Application\Service\MiseEnPaiementIntervenantStructureService;

/**
 * Description of MiseEnPaiementIntervenantStructureAwareTrait
 *
 * @author UnicaenCode
 */
trait MiseEnPaiementIntervenantStructureServiceAwareTrait
{
    /**
     * @var MiseEnPaiementIntervenantStructureService
     */
    private $serviceMiseEnPaiementIntervenantStructure;



    /**
     * @param MiseEnPaiementIntervenantStructureService $serviceMiseEnPaiementIntervenantStructure
     *
     * @return self
     */
    public function setServiceMiseEnPaiementIntervenantStructure(MiseEnPaiementIntervenantStructureService $serviceMiseEnPaiementIntervenantStructure)
    {
        $this->serviceMiseEnPaiementIntervenantStructure = $serviceMiseEnPaiementIntervenantStructure;

        return $this;
    }



    /**
     * @return MiseEnPaiementIntervenantStructureService
     */
    public function getServiceMiseEnPaiementIntervenantStructure()
    {
        if (empty($this->serviceMiseEnPaiementIntervenantStructure)) {
            $this->serviceMiseEnPaiementIntervenantStructure = \Application::$container->get(MiseEnPaiementIntervenantStructureService::class);
        }

        return $this->serviceMiseEnPaiementIntervenantStructure;
    }
}