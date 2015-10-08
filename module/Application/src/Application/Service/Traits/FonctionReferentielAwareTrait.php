<?php

namespace Application\Service\Traits;

use Application\Service\FonctionReferentiel;
use Application\Module;
use RuntimeException;

/**
 * Description of FonctionReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait FonctionReferentielAwareTrait
{
    /**
     * @var FonctionReferentiel
     */
    private $serviceFonctionReferentiel;





    /**
     * @param FonctionReferentiel $serviceFonctionReferentiel
     * @return self
     */
    public function setServiceFonctionReferentiel( FonctionReferentiel $serviceFonctionReferentiel )
    {
        $this->serviceFonctionReferentiel = $serviceFonctionReferentiel;
        return $this;
    }



    /**
     * @return FonctionReferentiel
     * @throws RuntimeException
     */
    public function getServiceFonctionReferentiel()
    {
        if (empty($this->serviceFonctionReferentiel)){
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
        $this->serviceFonctionReferentiel = $serviceLocator->get('ApplicationFonctionReferentiel');
        }
        return $this->serviceFonctionReferentiel;
    }
}