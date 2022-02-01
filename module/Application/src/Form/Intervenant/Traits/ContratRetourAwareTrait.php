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
    protected ?ContratRetour $formIntervenantContratRetour;



    /**
     * @param ContratRetour|null $formIntervenantContratRetour
     *
     * @return self
     */
    public function setFormIntervenantContratRetour( ?ContratRetour $formIntervenantContratRetour )
    {
        $this->formIntervenantContratRetour = $formIntervenantContratRetour;

        return $this;
    }



    public function getFormIntervenantContratRetour(): ?ContratRetour
    {
        if (!$this->formIntervenantContratRetour){
            $this->formIntervenantContratRetour = \Application::$container->get('FormElementManager')->get(ContratRetour::class);
        }

        return $this->formIntervenantContratRetour;
    }
}