<?php

namespace Dossier\Form\Traits;

use Dossier\Form\EmployeurSaisieForm;

trait EmployeurSaisieFormAwareTrait
{
    protected ?EmployeurSaisieForm $formEmployeurSaisie = null;



    /**
     * @param EmployeurSaisieForm $formEmployeurSaisie
     *
     * @return self
     */
    public function setFormEmployeurSaisie(?EmployeurSaisieForm $formEmployeurSaisie)
    {
        $this->formEmployeurSaisie = $formEmployeurSaisie;

        return $this;
    }



    public function getFormEmployeurSaisie(): ?EmployeurSaisieForm
    {
        if (!empty($this->formEmployeurSaisie)) {
            return $this->formEmployeurSaisie;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(EmployeurSaisieForm::class);
    }
}