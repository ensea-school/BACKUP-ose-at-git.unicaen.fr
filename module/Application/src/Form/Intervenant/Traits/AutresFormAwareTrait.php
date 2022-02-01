<?php

namespace Application\Form\Intervenant\Traits;

use Application\Form\Intervenant\AutresForm;

/**
 * Description of AutresFormAwareTrait
 *
 * @author UnicaenCode
 */
trait AutresFormAwareTrait
{
    protected ?AutresForm $formIntervenantAutres;



    /**
     * @param AutresForm|null $formIntervenantAutres
     *
     * @return self
     */
    public function setFormIntervenantAutres( ?AutresForm $formIntervenantAutres )
    {
        $this->formIntervenantAutres = $formIntervenantAutres;

        return $this;
    }



    public function getFormIntervenantAutres(): ?AutresForm
    {
        if (!$this->formIntervenantAutres){
            $this->formIntervenantAutres = \Application::$container->get('FormElementManager')->get(AutresForm::class);
        }

        return $this->formIntervenantAutres;
    }
}