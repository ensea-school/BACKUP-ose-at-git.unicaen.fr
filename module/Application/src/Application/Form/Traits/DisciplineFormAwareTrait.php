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
     * @return DisciplineForm
     * @throws RuntimeException
     */
    public function getFormDiscipline()
    {
        if (empty($this->formDiscipline)){
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
            $this->formDiscipline = $serviceLocator->get('FormElementManager')->get('DisciplineForm');
        }
        return $this->formDiscipline;
    }
}