<?php

namespace Dossier\Form\Traits;


use Dossier\Form\AutresForm;

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

         return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(AutresForm::class);
    }
}