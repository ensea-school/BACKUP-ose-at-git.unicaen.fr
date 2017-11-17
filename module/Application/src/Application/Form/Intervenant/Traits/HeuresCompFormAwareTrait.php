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
    /**
     * @var HeuresCompForm
     */
    private $formIntervenantHeuresComp;



    /**
     * @param HeuresCompForm $formIntervenantHeuresComp
     *
     * @return self
     */
    public function setFormIntervenantHeuresComp(HeuresCompForm $formIntervenantHeuresComp)
    {
        $this->formIntervenantHeuresComp = $formIntervenantHeuresComp;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return HeuresCompForm
     */
    public function getFormIntervenantHeuresComp()
    {
        if (!empty($this->formIntervenantHeuresComp)) {
            return $this->formIntervenantHeuresComp;
        }

        return \Application::$container->get('FormElementManager')->get('IntervenantHeuresCompForm');
    }
}