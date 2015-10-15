<?php

namespace Application\Form\Intervenant\Traits;

use Application\Form\Intervenant\ModificationServiceDuFieldset;
use Application\Module;
use RuntimeException;

/**
 * Description of ModificationServiceDuFieldsetAwareTrait
 *
 * @author UnicaenCode
 */
trait ModificationServiceDuFieldsetAwareTrait
{
    /**
     * @var ModificationServiceDuFieldset
     */
    private $fieldsetIntervenantModificationServiceDu;





    /**
     * @param ModificationServiceDuFieldset $fieldsetIntervenantModificationServiceDu
     * @return self
     */
    public function setFieldsetIntervenantModificationServiceDu( ModificationServiceDuFieldset $fieldsetIntervenantModificationServiceDu )
    {
        $this->fieldsetIntervenantModificationServiceDu = $fieldsetIntervenantModificationServiceDu;
        return $this;
    }



    /**
     * @return ModificationServiceDuFieldset
     * @throws RuntimeException
     */
    public function getFieldsetIntervenantModificationServiceDu()
    {
        if (empty($this->fieldsetIntervenantModificationServiceDu)){
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
            $this->fieldsetIntervenantModificationServiceDu = $serviceLocator->get('FormElementManager')->get('IntervenantModificationServiceDuFieldset');
        }
        return $this->fieldsetIntervenantModificationServiceDu;
    }
}