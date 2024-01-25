<?php

namespace Formule\Form;

/**
 * Description of HeuresCompFormAwareTrait
 *
 * @author UnicaenCode
 */
trait HeuresCompFormAwareTrait
{
    protected ?HeuresCompForm $formIntervenantHeuresComp = null;



    /**
     * @param HeuresCompForm $formIntervenantHeuresComp
     *
     * @return self
     */
    public function setFormIntervenantHeuresComp(?HeuresCompForm $formIntervenantHeuresComp)
    {
        $this->formIntervenantHeuresComp = $formIntervenantHeuresComp;

        return $this;
    }



    public function getFormIntervenantHeuresComp(): ?HeuresCompForm
    {
        if (!empty($this->formIntervenantHeuresComp)) {
            return $this->formIntervenantHeuresComp;
        }

        return \OseAdmin::instance()->container()->get('FormElementManager')->get(HeuresCompForm::class);
    }
}