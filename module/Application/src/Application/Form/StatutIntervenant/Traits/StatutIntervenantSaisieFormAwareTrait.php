<?php

namespace Application\Form\StatutIntervenant\Traits;

use Application\Form\StatutIntervenant\StatutIntervenantSaisieForm;
use Application\Module;
use RuntimeException;

/**
 * Description of StatutIntervenantSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait StatutIntervenantSaisieFormAwareTrait
{
    /**
     * @var StatutIntervenantSaisieForm
     */
    private $formStatutIntervenantSaisie;


    /**
     * @param StatutIntervenantSaisieForm $formStatutIntervenantSaisie
     * @return self
     */
    public function setFormStatutIntervenantSaisie( StatutIntervenantSaisieForm $formStatutIntervenantSaisie )
    {
        $this->formStatutIntervenantSaisie = $formStatutIntervenantSaisie;
        return $this;
    }


    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return StatutIntervenantSaisieForm
     * @throws RuntimeException
     */
    public function getFormStatutIntervenantSaisie()
    {
        if (!empty($this->formStatutIntervenantSaisie)){
            return $this->formStatutIntervenantSaisie;
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
        return $serviceLocator->get('FormElementManager')->get('StatutIntervenantSaisie');
    }
}

