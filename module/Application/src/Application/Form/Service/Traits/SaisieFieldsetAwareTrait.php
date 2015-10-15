<?php

namespace Application\Form\Service\Traits;

use Application\Form\Service\SaisieFieldset;
use Application\Module;
use RuntimeException;

/**
 * Description of SaisieFieldsetAwareTrait
 *
 * @author UnicaenCode
 */
trait SaisieFieldsetAwareTrait
{
    /**
     * @var SaisieFieldset
     */
    private $fieldsetServiceSaisie;





    /**
     * @param SaisieFieldset $fieldsetServiceSaisie
     * @return self
     */
    public function setFieldsetServiceSaisie( SaisieFieldset $fieldsetServiceSaisie )
    {
        $this->fieldsetServiceSaisie = $fieldsetServiceSaisie;
        return $this;
    }



    /**
     * @return SaisieFieldset
     * @throws RuntimeException
     */
    public function getFieldsetServiceSaisie()
    {
        if (empty($this->fieldsetServiceSaisie)){
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
            $this->fieldsetServiceSaisie = $serviceLocator->get('FormElementManager')->get('ServiceSaisieFieldset');
        }
        return $this->fieldsetServiceSaisie;
    }
}