<?php

namespace Application\Service\Traits;

use Application\Service\FormuleResultatServiceReferentiel;
use Application\Module;
use RuntimeException;

/**
 * Description of FormuleResultatServiceReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatServiceReferentielAwareTrait
{
    /**
     * @var FormuleResultatServiceReferentiel
     */
    private $serviceFormuleResultatServiceReferentiel;





    /**
     * @param FormuleResultatServiceReferentiel $serviceFormuleResultatServiceReferentiel
     * @return self
     */
    public function setServiceFormuleResultatServiceReferentiel( FormuleResultatServiceReferentiel $serviceFormuleResultatServiceReferentiel )
    {
        $this->serviceFormuleResultatServiceReferentiel = $serviceFormuleResultatServiceReferentiel;
        return $this;
    }



    /**
     * @return FormuleResultatServiceReferentiel
     * @throws RuntimeException
     */
    public function getServiceFormuleResultatServiceReferentiel()
    {
        if (empty($this->serviceFormuleResultatServiceReferentiel)){
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
        $this->serviceFormuleResultatServiceReferentiel = $serviceLocator->get('ApplicationFormuleResultatServiceReferentiel');
        }
        return $this->serviceFormuleResultatServiceReferentiel;
    }
}