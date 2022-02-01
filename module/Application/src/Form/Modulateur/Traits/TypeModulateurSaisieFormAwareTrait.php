<?php

namespace Application\Form\Modulateur\Traits;

use Application\Form\Modulateur\TypeModulateurSaisieForm;

/**
 * Description of TypeModulateurSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeModulateurSaisieFormAwareTrait
{
    protected ?TypeModulateurSaisieForm $formModulateurTypeModulateurSaisie;



    /**
     * @param TypeModulateurSaisieForm|null $formModulateurTypeModulateurSaisie
     *
     * @return self
     */
    public function setFormModulateurTypeModulateurSaisie( ?TypeModulateurSaisieForm $formModulateurTypeModulateurSaisie )
    {
        $this->formModulateurTypeModulateurSaisie = $formModulateurTypeModulateurSaisie;

        return $this;
    }



    public function getFormModulateurTypeModulateurSaisie(): ?TypeModulateurSaisieForm
    {
        if (!$this->formModulateurTypeModulateurSaisie){
            $this->formModulateurTypeModulateurSaisie = \Application::$container->get('FormElementManager')->get(TypeModulateurSaisieForm::class);
        }

        return $this->formModulateurTypeModulateurSaisie;
    }
}