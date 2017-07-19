<?php

namespace Application\Form\Intervenant\Traits;

use Application\Form\Intervenant\EditionForm;
use Application\Module;
use RuntimeException;

/**
 * Description of EditionFormAwareTrait
 *
 * @author UnicaenCode
 */
trait EditionFormAwareTrait
{
    /**
     * @var EditionForm
     */
    private $formIntervenantEdition;





    /**
     * @param EditionForm $formIntervenantEdition
     * @return self
     */
    public function setFormIntervenantEdition( EditionForm $formIntervenantEdition )
    {
        $this->formIntervenantEdition = $formIntervenantEdition;
        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return EditionForm
     * @throws RuntimeException
     */
    public function getFormIntervenantEdition()
    {
        if (!empty($this->formIntervenantEdition)){
            return $this->formIntervenantEdition;
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
        return $serviceLocator->get('FormElementManager')->get('IntervenantEditionForm');
    }
}