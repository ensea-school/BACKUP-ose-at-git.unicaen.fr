<?php

namespace Application\Service\Traits;

use Application\Service\FormuleServiceReferentiel;
use Application\Module;
use RuntimeException;

/**
 * Description of FormuleServiceReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleServiceReferentielAwareTrait
{
    /**
     * @var FormuleServiceReferentiel
     */
    private $serviceFormuleServiceReferentiel;





    /**
     * @param FormuleServiceReferentiel $serviceFormuleServiceReferentiel
     * @return self
     */
    public function setServiceFormuleServiceReferentiel( FormuleServiceReferentiel $serviceFormuleServiceReferentiel )
    {
        $this->serviceFormuleServiceReferentiel = $serviceFormuleServiceReferentiel;
        return $this;
    }



    /**
     * @return FormuleServiceReferentiel
     * @throws RuntimeException
     */
    public function getServiceFormuleServiceReferentiel()
    {
        if (empty($this->serviceFormuleServiceReferentiel)){
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
        $this->serviceFormuleServiceReferentiel = $serviceLocator->get('ApplicationFormuleServiceReferentiel');
        }
        return $this->serviceFormuleServiceReferentiel;
    }
}