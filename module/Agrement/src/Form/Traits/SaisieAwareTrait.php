<?php

namespace Agrement\Form\Traits;

use Agrement\Form\Saisie;

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
    public function setFormAgrementSaisie(?Saisie $formAgrementSaisie)
    {
        $this->formAgrementSaisie = $formAgrementSaisie;

        return $this;
    }



    public function getFormAgrementSaisie(): ?Saisie
    {
        if (!empty($this->formAgrementSaisie)) {
            return $this->formAgrementSaisie;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(Saisie::class);
    }
}