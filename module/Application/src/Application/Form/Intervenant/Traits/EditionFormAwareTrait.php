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
    /**
     * @var EditionForm
     */
    private $formIntervenantEdition;



    /**
     * @param EditionForm $formIntervenantEdition
     *
     * @return self
     */
    public function setFormIntervenantEdition(EditionForm $formIntervenantEdition)
    {
        $this->formIntervenantEdition = $formIntervenantEdition;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return EditionForm
     */
    public function getFormIntervenantEdition()
    {
        if (!empty($this->formIntervenantEdition)) {
            return $this->formIntervenantEdition;
        }

        return \Application::$container->get('FormElementManager')->get(EditionForm::class);
    }
}