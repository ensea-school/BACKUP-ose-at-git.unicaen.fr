<?php

namespace Application\Form\Traits;

use Application\Form\DisciplineForm;
use Application\Module;
use RuntimeException;

/**
 * Description of DisciplineFormAwareTrait
 *
 * @author UnicaenCode
 */
trait DisciplineFormAwareTrait
{
    /**
     * @var DisciplineForm
     */
    private $formDiscipline;





    /**
     * @param DisciplineForm $formDiscipline
     * @return self
     */
    public function setFormDiscipline( DisciplineForm $formDiscipline )
    {
        $this->formDiscipline = $formDiscipline;
        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return DisciplineForm
     * @throws RuntimeException
     */
    public function getFormDiscipline()
    {
        if (!empty($this->formDiscipline)){
            return $this->formDiscipline;
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
        return $serviceLocator->get('FormElementManager')->get('DisciplineForm');
    }
}