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
    protected ?Saisie $formAgrementSaisie = null;



    /**
     * @param Saisie $formAgrementSaisie
     *
     * @return self
     */
    public function setFormAgrementSaisie( Saisie $formAgrementSaisie )
    {
        $this->formAgrementSaisie = $formAgrementSaisie;

        return $this;
    }



    public function getFormAgrementSaisie(): ?Saisie
    {
        if (empty($this->formAgrementSaisie)){
            $this->formAgrementSaisie = \Application::$container->get('FormElementManager')->get(Saisie::class);
        }

        return $this->formAgrementSaisie;
    }
}