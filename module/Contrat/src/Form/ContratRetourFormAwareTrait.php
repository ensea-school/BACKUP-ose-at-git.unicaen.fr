<?php

namespace Contrat\Form;


/**
 * Description of ContratRetourFormAwareTrait
 *
 * @author UnicaenCode
 */
trait ContratRetourFormAwareTrait
{
    protected ?ContratRetourForm $formIntervenantContratRetourForm = null;



    /**
     * @param ContratRetourForm $formIntervenantContratRetourForm
     *
     * @return self
     */
    public function setFormIntervenantContratRetourForm(?ContratRetourForm $formIntervenantContratRetourForm)
    {
        $this->formIntervenantContratRetourForm = $formIntervenantContratRetourForm;

        return $this;
    }



    public function getFormIntervenantContratRetourForm(): ?ContratRetourForm
    {
        if (!empty($this->formIntervenantContratRetourForm)) {
            return $this->formIntervenantContratRetourForm;
        }

        return \OseAdmin::instance()->container()->get('FormElementManager')->get(ContratRetourForm::class);
    }
}