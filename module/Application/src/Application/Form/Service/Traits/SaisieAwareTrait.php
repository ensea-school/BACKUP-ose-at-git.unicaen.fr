<?php

namespace Application\Form\Service\Traits;

use Application\Form\Service\Saisie;
use Application\Module;
use RuntimeException;

/**
 * Description of SaisieAwareTrait
 *
 * @author UnicaenCode
 */
trait SaisieAwareTrait
{
    /**
     * @var Saisie
     */
    private $formServiceSaisie;





    /**
     * @param Saisie $formServiceSaisie
     * @return self
     */
    public function setFormServiceSaisie( Saisie $formServiceSaisie )
    {
        $this->formServiceSaisie = $formServiceSaisie;
        return $this;
    }



    /**
     * @return Saisie
     * @throws RuntimeException
     */
    public function getFormServiceSaisie()
    {
        if (empty($this->formServiceSaisie)){
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
            $this->formServiceSaisie = $serviceLocator->getServiceLocator('FormElementManager')->get('ServiceSaisie');
        }
        return $this->formServiceSaisie;
    }
}