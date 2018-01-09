<?php

namespace Application\Form\Intervenant\Traits;

use Application\Form\Intervenant\Dossier;

/**
 * Description of DossierAwareTrait
 *
 * @author UnicaenCode
 */
trait DossierAwareTrait
{
    /**
     * @var Dossier
     */
    private $formIntervenantDossier;



    /**
     * @param Dossier $formIntervenantDossier
     *
     * @return self
     */
    public function setFormIntervenantDossier(Dossier $formIntervenantDossier)
    {
        $this->formIntervenantDossier = $formIntervenantDossier;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return Dossier
     */
    public function getFormIntervenantDossier()
    {
        if (!empty($this->formIntervenantDossier)) {
            return $this->formIntervenantDossier;
        }

        return \Application::$container->get('FormElementManager')->get(Dossier::class);
    }
}