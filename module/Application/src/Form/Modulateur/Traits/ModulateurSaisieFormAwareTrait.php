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
    protected ?ModulateurSaisieForm $formModulateurModulateurSaisie;



    /**
     * @param ModulateurSaisieForm|null $formModulateurModulateurSaisie
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
        if (!$this->formModulateurModulateurSaisie){
            $this->formModulateurModulateurSaisie = \Application::$container->get('FormElementManager')->get(ModulateurSaisieForm::class);
        }

        return $this->formModulateurModulateurSaisie;
    }
}