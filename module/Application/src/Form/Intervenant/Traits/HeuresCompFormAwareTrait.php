<?php

namespace Application\Form\Intervenant\Traits;

use Application\Form\Intervenant\HeuresCompForm;

/**
 * Description of HeuresCompFormAwareTrait
 *
 * @author UnicaenCode
 */
trait HeuresCompFormAwareTrait
{
    protected ?HeuresCompForm $formIntervenantHeuresComp;



    /**
     * @param HeuresCompForm|null $formIntervenantHeuresComp
     *
     * @return self
     */
    public function setFormIntervenantHeuresComp( ?HeuresCompForm $formIntervenantHeuresComp )
    {
        $this->formIntervenantHeuresComp = $formIntervenantHeuresComp;

        return $this;
    }



    public function getFormIntervenantHeuresComp(): ?HeuresCompForm
    {
        if (!$this->formIntervenantHeuresComp){
            $this->formIntervenantHeuresComp = \Application::$container->get('FormElementManager')->get(HeuresCompForm::class);
        }

        return $this->formIntervenantHeuresComp;
    }
}