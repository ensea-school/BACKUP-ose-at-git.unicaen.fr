<?php

namespace Application\Hydrator\Service\Traits;

use Application\Hydrator\Service\RechercheHydrator;
use Application\Module;
use RuntimeException;

/**
 * Description of RechercheHydratorAwareTrait
 *
 * @author UnicaenCode
 */
trait RechercheHydratorAwareTrait
{
    /**
     * @var RechercheHydrator
     */
    private $hydratorServiceRecherche;





    /**
     * @param RechercheHydrator $hydratorServiceRecherche
     * @return self
     */
    public function setHydratorServiceRecherche( RechercheHydrator $hydratorServiceRecherche )
    {
        $this->hydratorServiceRecherche = $hydratorServiceRecherche;
        return $this;
    }



    /**
     * @return RechercheHydrator
     * @throws RuntimeException
     */
    public function getHydratorServiceRecherche()
    {
        if (empty($this->hydratorServiceRecherche)){
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
        $this->hydratorServiceRecherche = $serviceLocator->get('HydratorManager')->get('serviceRecherche');
        }
        return $this->hydratorServiceRecherche;
    }
}