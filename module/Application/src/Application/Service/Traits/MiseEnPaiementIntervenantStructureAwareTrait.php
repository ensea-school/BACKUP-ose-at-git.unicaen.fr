<?php

namespace Application\Service\Traits;

use Application\Service\MiseEnPaiementIntervenantStructure;
use Application\Module;
use RuntimeException;

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
     * @return self
     */
    public function setServiceMiseEnPaiementIntervenantStructure( MiseEnPaiementIntervenantStructure $serviceMiseEnPaiementIntervenantStructure )
    {
        $this->serviceMiseEnPaiementIntervenantStructure = $serviceMiseEnPaiementIntervenantStructure;
        return $this;
    }



    /**
     * @return MiseEnPaiementIntervenantStructure
     * @throws RuntimeException
     */
    public function getServiceMiseEnPaiementIntervenantStructure()
    {
        if (empty($this->serviceMiseEnPaiementIntervenantStructure)){
        $serviceLocator = Module::$serviceLocator;
        if (! $serviceLocator) {
            if (!method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException('La classe ' . get_class($this) . ' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }
        }
        $this->serviceMiseEnPaiementIntervenantStructure = $serviceLocator->get('ApplicationMiseEnPaiementIntervenantStructure');
        }
        return $this->serviceMiseEnPaiementIntervenantStructure;
    }
}