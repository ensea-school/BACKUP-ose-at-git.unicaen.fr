<?php

namespace Application\Form\Droits\Traits;

use Application\Form\Droits\AffectationForm;
use Application\Module;
use RuntimeException;

/**
 * Description of AffectationFormAwareTrait
 *
 * @author UnicaenCode
 */
trait AffectationFormAwareTrait
{
    /**
     * @var AffectationForm
     */
    private $formDroitsAffectation;





    /**
     * @param AffectationForm $formDroitsAffectation
     * @return self
     */
    public function setFormDroitsAffectation( AffectationForm $formDroitsAffectation )
    {
        $this->formDroitsAffectation = $formDroitsAffectation;
        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return AffectationForm
     * @throws RuntimeException
     */
    public function getFormDroitsAffectation()
    {
        if (!empty($this->formDroitsAffectation)){
            return $this->formDroitsAffectation;
        }

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
        return $serviceLocator->get('FormElementManager')->get('DroitsAffectationForm');
    }
}