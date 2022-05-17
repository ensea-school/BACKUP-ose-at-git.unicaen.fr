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
    protected ?AutresForm $formIntervenantAutres = null;



    /**
     * @param AutresForm $formIntervenantAutres
     *
     * @return self
     */
    public function setFormIntervenantAutres(?AutresForm $formIntervenantAutres)
    {
        $this->formIntervenantAutres = $formIntervenantAutres;

        return $this;
    }



    public function getFormIntervenantAutres(): ?AutresForm
    {
        if (!empty($this->formIntervenantAutres)) {
            return $this->formIntervenantAutres;
        }

        return \Application::$container->get('FormElementManager')->get(AutresForm::class);
    }
}