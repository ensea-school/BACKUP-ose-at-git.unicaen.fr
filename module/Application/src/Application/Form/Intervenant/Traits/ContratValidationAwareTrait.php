<?php

namespace Application\Form\Intervenant\Traits;

use Application\Form\Intervenant\ContratValidation;
use Application\Module;
use RuntimeException;

/**
 * Description of ContratValidationAwareTrait
 *
 * @author UnicaenCode
 */
trait ContratValidationAwareTrait
{
    /**
     * @var ContratValidation
     */
    private $formIntervenantContratValidation;





    /**
     * @param ContratValidation $formIntervenantContratValidation
     * @return self
     */
    public function setFormIntervenantContratValidation( ContratValidation $formIntervenantContratValidation )
    {
        $this->formIntervenantContratValidation = $formIntervenantContratValidation;
        return $this;
    }



    /**
     * @return ContratValidation
     * @throws RuntimeException
     */
    public function getFormIntervenantContratValidation()
    {
        if (empty($this->formIntervenantContratValidation)){
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
            $this->formIntervenantContratValidation = $serviceLocator->get('FormElementManager')->get('contratValivation');
        }
        return $this->formIntervenantContratValidation;
    }
}