<?php

namespace Intervenant\Form;

/**
 * Description of EditionFormAwareTrait
 *
 * @author UnicaenCode
 */
trait EditionFormAwareTrait
{
    protected ?EditionForm $formIntervenantEdition = null;



    /**
     * @param EditionForm $formIntervenantEdition
     *
     * @return self
     */
    public function setFormIntervenantEdition(?EditionForm $formIntervenantEdition)
    {
        $this->formIntervenantEdition = $formIntervenantEdition;

        return $this;
    }



    public function getFormIntervenantEdition(): ?EditionForm
    {
        if (!empty($this->formIntervenantEdition)) {
            return $this->formIntervenantEdition;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(EditionForm::class);
    }
}