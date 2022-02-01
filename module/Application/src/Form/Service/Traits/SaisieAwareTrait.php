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
    protected ?Saisie $formServiceSaisie = null;



    /**
     * @param Saisie $formServiceSaisie
     *
     * @return self
     */
    public function setFormServiceSaisie( ?Saisie $formServiceSaisie )
    {
        $this->formServiceSaisie = $formServiceSaisie;

        return $this;
    }



    public function getFormServiceSaisie(): ?Saisie
    {
        if (empty($this->formServiceSaisie)){
            $this->formServiceSaisie = \Application::$container->get('FormElementManager')->get(Saisie::class);
        }

        return $this->formServiceSaisie;
    }
}