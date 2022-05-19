<?php

namespace Application\Form\Intervenant\Traits;

use Application\Form\Intervenant\IntervenantDossierForm;

/**
 * Description of IntervenantDossierFormAwareTrait
 *
 * @author UnicaenCode
 */
trait IntervenantDossierFormAwareTrait
{
    protected ?IntervenantDossierForm $formIntervenantIntervenantDossier = null;



    /**
     * @param IntervenantDossierForm $formIntervenantIntervenantDossier
     *
     * @return self
     */
    public function setFormIntervenantIntervenantDossier(?IntervenantDossierForm $formIntervenantIntervenantDossier)
    {
        $this->formIntervenantIntervenantDossier = $formIntervenantIntervenantDossier;

        return $this;
    }



    public function getFormIntervenantIntervenantDossier(): ?IntervenantDossierForm
    {
        if (!empty($this->formIntervenantIntervenantDossier)) {
            return $this->formIntervenantIntervenantDossier;
        }

        return \Application::$container->get('FormElementManager')->get(IntervenantDossierForm::class);
    }
}