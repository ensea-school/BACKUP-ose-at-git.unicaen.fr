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
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return SaisieFieldset
     * @throws RuntimeException
     */
    public function getFieldsetServiceSaisie()
    {
        if (!empty($this->fieldsetServiceSaisie)){
            return $this->fieldsetServiceSaisie;
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
        return $serviceLocator->get('FormElementManager')->get('ServiceSaisieFieldset');
    }
}