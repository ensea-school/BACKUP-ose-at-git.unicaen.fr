<?php

namespace Application\Service\Traits;

use Application\Service\MiseEnPaiementIntervenantStructure;

/**
 * Description of MiseEnPaiementIntervenantStructureAwareTrait
 *
 * @author UnicaenCode
 */
trait MiseEnPaiementIntervenantStructureAwareTrait
{
    /**
     * @var MiseEnPaiementIntervenantStructure
     */
    private $serviceMiseEnPaiementIntervenantStructure;



    /**
     * @param MiseEnPaiementIntervenantStructure $serviceMiseEnPaiementIntervenantStructure
     *
     * @return self
     */
    public function setServiceMiseEnPaiementIntervenantStructure(MiseEnPaiementIntervenantStructure $serviceMiseEnPaiementIntervenantStructure)
    {
        $this->serviceMiseEnPaiementIntervenantStructure = $serviceMiseEnPaiementIntervenantStructure;

        return $this;
    }



    /**
     * @return MiseEnPaiementIntervenantStructure
     */
    public function getServiceMiseEnPaiementIntervenantStructure()
    {
        if (empty($this->serviceMiseEnPaiementIntervenantStructure)) {
            $this->serviceMiseEnPaiementIntervenantStructure = \Application::$container->get('ApplicationMiseEnPaiementIntervenantStructure');
        }

        return $this->serviceMiseEnPaiementIntervenantStructure;
    }
}