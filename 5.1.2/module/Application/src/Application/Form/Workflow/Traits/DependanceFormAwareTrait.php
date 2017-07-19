<?php

namespace Application\Form\Workflow\Traits;

use Application\Form\Workflow\DependanceForm;
use Application\Module;
use RuntimeException;

/**
 * Description of DependanceFormAwareTrait
 *
 * @author UnicaenCode
 */
trait DependanceFormAwareTrait
{
    /**
     * @var DependanceForm
     */
    private $formWorkflowDependance;





    /**
     * @param DependanceForm $formWorkflowDependance
     * @return self
     */
    public function setFormWorkflowDependance( DependanceForm $formWorkflowDependance )
    {
        $this->formWorkflowDependance = $formWorkflowDependance;
        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return DependanceForm
     * @throws RuntimeException
     */
    public function getFormWorkflowDependance()
    {
        if (!empty($this->formWorkflowDependance)){
            return $this->formWorkflowDependance;
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
        return $serviceLocator->get('FormElementManager')->get('WorkflowDependance');
    }
}