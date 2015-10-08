<?php

namespace Application\Form\OffreFormation\Traits;

use Application\Form\OffreFormation\ElementModulateursFieldset;
use Application\Module;
use RuntimeException;

/**
 * Description of ElementModulateursFieldsetAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementModulateursFieldsetAwareTrait
{
    /**
     * @var ElementModulateursFieldset
     */
    private $fieldsetOffreFormationElementModulateurs;





    /**
     * @param ElementModulateursFieldset $fieldsetOffreFormationElementModulateurs
     * @return self
     */
    public function setFieldsetOffreFormationElementModulateurs( ElementModulateursFieldset $fieldsetOffreFormationElementModulateurs )
    {
        $this->fieldsetOffreFormationElementModulateurs = $fieldsetOffreFormationElementModulateurs;
        return $this;
    }



    /**
     * @return ElementModulateursFieldset
     * @throws RuntimeException
     */
    public function getFieldsetOffreFormationElementModulateurs()
    {
        if (empty($this->fieldsetOffreFormationElementModulateurs)){
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
            $this->fieldsetOffreFormationElementModulateurs = $serviceLocator->getServiceLocator('FormElementManager')->get('ElementModulateursFieldset');
        }
        return $this->fieldsetOffreFormationElementModulateurs;
    }
}