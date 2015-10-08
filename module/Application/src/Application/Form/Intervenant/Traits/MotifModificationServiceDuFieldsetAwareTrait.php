<?php

namespace Application\Form\Intervenant\Traits;

use Application\Form\Intervenant\MotifModificationServiceDuFieldset;
use Application\Module;
use RuntimeException;

/**
 * Description of MotifModificationServiceDuFieldsetAwareTrait
 *
 * @author UnicaenCode
 */
trait MotifModificationServiceDuFieldsetAwareTrait
{
    /**
     * @var MotifModificationServiceDuFieldset
     */
    private $fieldsetIntervenantMotifModificationServiceDu;





    /**
     * @param MotifModificationServiceDuFieldset $fieldsetIntervenantMotifModificationServiceDu
     * @return self
     */
    public function setFieldsetIntervenantMotifModificationServiceDu( MotifModificationServiceDuFieldset $fieldsetIntervenantMotifModificationServiceDu )
    {
        $this->fieldsetIntervenantMotifModificationServiceDu = $fieldsetIntervenantMotifModificationServiceDu;
        return $this;
    }



    /**
     * @return MotifModificationServiceDuFieldset
     * @throws RuntimeException
     */
    public function getFieldsetIntervenantMotifModificationServiceDu()
    {
        if (empty($this->fieldsetIntervenantMotifModificationServiceDu)){
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
            $this->fieldsetIntervenantMotifModificationServiceDu = $serviceLocator->getServiceLocator('FormElementManager')->get('IntervenantMotifModificationServiceDuFieldset');
        }
        return $this->fieldsetIntervenantMotifModificationServiceDu;
    }
}