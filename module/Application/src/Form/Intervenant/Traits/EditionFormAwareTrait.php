<?php

namespace Application\Form\Intervenant\Traits;

use Application\Form\Intervenant\EditionForm;

/**
 * Description of EditionFormAwareTrait
 *
 * @author UnicaenCode
 */
trait EditionFormAwareTrait
{
    protected ?EditionForm $formIntervenantEdition;



    /**
     * @param EditionForm|null $formIntervenantEdition
     *
     * @return self
     */
    public function setFormIntervenantEdition( ?EditionForm $formIntervenantEdition )
    {
        $this->formIntervenantEdition = $formIntervenantEdition;

        return $this;
    }



    public function getFormIntervenantEdition(): ?EditionForm
    {
        if (!$this->formIntervenantEdition){
            $this->formIntervenantEdition = \Application::$container->get('FormElementManager')->get(EditionForm::class);
        }

        return $this->formIntervenantEdition;
    }
}