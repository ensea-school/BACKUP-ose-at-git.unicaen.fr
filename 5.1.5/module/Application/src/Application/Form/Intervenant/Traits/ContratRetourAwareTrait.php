<?php

namespace Application\Form\Intervenant\Traits;

use Application\Form\Intervenant\ContratRetour;
use Application\Module;
use RuntimeException;

/**
 * Description of ContratRetourAwareTrait
 *
 * @author UnicaenCode
 */
trait ContratRetourAwareTrait
{
    /**
     * @var ContratRetour
     */
    private $formIntervenantContratRetour;





    /**
     * @param ContratRetour $formIntervenantContratRetour
     * @return self
     */
    public function setFormIntervenantContratRetour( ContratRetour $formIntervenantContratRetour )
    {
        $this->formIntervenantContratRetour = $formIntervenantContratRetour;
        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return ContratRetour
     * @throws RuntimeException
     */
    public function getFormIntervenantContratRetour()
    {
        if (!empty($this->formIntervenantContratRetour)){
            return $this->formIntervenantContratRetour;
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
        return $serviceLocator->get('FormElementManager')->get('contratRetour');
    }
}