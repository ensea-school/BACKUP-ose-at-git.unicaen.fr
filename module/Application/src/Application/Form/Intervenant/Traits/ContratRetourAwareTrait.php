<?php

namespace Application\Form\Intervenant\Traits;

use Application\Form\Intervenant\ContratRetour;

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
     *
     * @return self
     */
    public function setFormIntervenantContratRetour(ContratRetour $formIntervenantContratRetour)
    {
        $this->formIntervenantContratRetour = $formIntervenantContratRetour;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return ContratRetour
     */
    public function getFormIntervenantContratRetour()
    {
        if (!empty($this->formIntervenantContratRetour)) {
            return $this->formIntervenantContratRetour;
        }

        return \Application::$container->get('FormElementManager')->get(ContratRetour::class);
    }
}