<?php
namespace Application\Form\TypeIntervention\Traits;

use Application\Form\TypeIntervention\TypeInterventionStructureSaisieForm;
use Application\Module;
use RuntimeException;

/**
 * Description of TypeInterventionStructureSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeInterventionStructureSaisieFormAwareTrait
{
    /**
     * @var TypeInterventionStructureSaisieForm
     */
    private $formTypeInterventionStructureSaisie;


    /**
     * @param TypeInterventionStructureSaisieForm $formTypeInterventionStructureSaisie
     * @return self
     */
    public function setFormTypeInterventionStructureSaisie( TypeInterventionStructureSaisieForm $formTypeInterventionStructureSaisie )
    {
        $this->formTypeInterventionStructureSaisie = $formTypeInterventionStructureSaisie;
        return $this;
    }


    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return TypeInterventionStructureSaisieForm
     * @throws RuntimeException
     */
    public function getFormTypeInterventionStructureSaisie()
    {
        if (!empty($this->formTypeInterventionStructureSaisie)){
            return $this->formTypeInterventionStructureSaisie;
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
        return $serviceLocator->get('FormElementManager')->get('TypeInterventionStructureSaisie');
    }
}
