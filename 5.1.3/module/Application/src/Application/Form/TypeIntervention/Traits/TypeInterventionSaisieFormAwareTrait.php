<?php
namespace Application\Form\TypeIntervention\Traits;

use Application\Form\TypeIntervention\TypeInterventionSaisieForm;
use Application\Module;
use RuntimeException;

/**
 * Description of TypeInterventionSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeInterventionSaisieFormAwareTrait
{
    /**
     * @var TypeInterventionSaisieForm
     */
    private $formTypeInterventionSaisie;


    /**
     * @param TypeInterventionSaisieForm $formTypeInterventionSaisie
     * @return self
     */
    public function setFormTypeInterventionSaisie( TypeInterventionSaisieForm $formTypeInterventionSaisie )
    {
        $this->formTypeInterventionSaisie = $formTypeInterventionSaisie;
        return $this;
    }


    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return TypeInterventionSaisieForm
     * @throws RuntimeException
     */
    public function getFormTypeInterventionSaisie()
    {
        if (!empty($this->formTypeInterventionSaisie)){
            return $this->formTypeInterventionSaisie;
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
        return $serviceLocator->get('FormElementManager')->get('TypeInterventionSaisie');
    }
}
