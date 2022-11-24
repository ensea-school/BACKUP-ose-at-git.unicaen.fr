<?php

namespace Application\Form\Employeur\Traits;

use Application\Form\Employeur\EmployeurSaisieForm;

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

        return \Application::$container->get('FormElementManager')->get(EmployeurSaisieForm::class);
    }
}