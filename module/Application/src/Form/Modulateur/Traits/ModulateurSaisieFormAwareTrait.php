<?php

namespace Application\Form\Modulateur\Traits;

use Application\Form\Modulateur\ModulateurSaisieForm;

/**
 * Description of ModulateurSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait ModulateurSaisieFormAwareTrait
{
    protected ?ModulateurSaisieForm $formModulateurModulateurSaisie = null;



    /**
     * @param ModulateurSaisieForm $formModulateurModulateurSaisie
     *
     * @return self
     */
    public function setFormModulateurModulateurSaisie( ?ModulateurSaisieForm $formModulateurModulateurSaisie )
    {
        $this->formModulateurModulateurSaisie = $formModulateurModulateurSaisie;

        return $this;
    }



    public function getFormModulateurModulateurSaisie(): ?ModulateurSaisieForm
    {
        if (empty($this->formModulateurModulateurSaisie)){
            $this->formModulateurModulateurSaisie = \Application::$container->get('FormElementManager')->get(ModulateurSaisieForm::class);
        }

        return $this->formModulateurModulateurSaisie;
    }
}