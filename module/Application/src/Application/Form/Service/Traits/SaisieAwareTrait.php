<?php

namespace Application\Form\Service\Traits;

use Application\Form\Service\Saisie;

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
    private $formServiceSaisie;



    /**
     * @param Saisie $formServiceSaisie
     *
     * @return self
     */
    public function setFormServiceSaisie(Saisie $formServiceSaisie)
    {
        $this->formServiceSaisie = $formServiceSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return Saisie
     */
    public function getFormServiceSaisie()
    {
        if (!empty($this->formServiceSaisie)) {
            return $this->formServiceSaisie;
        }

        return \Application::$container->get('FormElementManager')->get('ServiceSaisie');
    }
}