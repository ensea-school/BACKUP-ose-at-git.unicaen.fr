<?php

namespace Application\Form\Agrement\Traits;

use Application\Form\Agrement\Saisie;

/**
 * Description of SaisieAwareTrait
 *
 * @author UnicaenCode
 */
trait SaisieAwareTrait
{
    /**
     * @var Saisie
     */
    private $formAgrementSaisie;



    /**
     * @param Saisie $formAgrementSaisie
     *
     * @return self
     */
    public function setFormAgrementSaisie(Saisie $formAgrementSaisie)
    {
        $this->formAgrementSaisie = $formAgrementSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return Saisie
     */
    public function getFormAgrementSaisie()
    {
        if (!empty($this->formAgrementSaisie)) {
            return $this->formAgrementSaisie;
        }

        return \Application::$container->get('FormElementManager')->get('AgrementSaisieForm');
    }
}