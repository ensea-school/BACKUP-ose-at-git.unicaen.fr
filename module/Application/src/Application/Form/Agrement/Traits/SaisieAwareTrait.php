<?php

namespace Application\Form\Agrement\Traits;

use Application\Form\Agrement\Saisie;
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
    private $formAgrementSaisie;





    /**
     * @param Saisie $formAgrementSaisie
     * @return self
     */
    public function setFormAgrementSaisie( Saisie $formAgrementSaisie )
    {
        $this->formAgrementSaisie = $formAgrementSaisie;
        return $this;
    }



    /**
     * @return Saisie
     * @throws RuntimeException
     */
    public function getFormAgrementSaisie()
    {
        if (empty($this->formAgrementSaisie)){
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
            $this->formAgrementSaisie = $serviceLocator->get('FormElementManager')->get('AgrementSaisieForm');
        }
        return $this->formAgrementSaisie;
    }
}