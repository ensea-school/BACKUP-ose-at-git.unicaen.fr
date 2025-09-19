<?php

namespace Paiement\Form\Modulateur;


/**
 * Description of TypeModulateurSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeModulateurSaisieFormAwareTrait
{
    protected ?TypeModulateurSaisieForm $formModulateurTypeModulateurSaisie = null;



    /**
     * @param TypeModulateurSaisieForm $formModulateurTypeModulateurSaisie
     *
     * @return self
     */
    public function setFormModulateurTypeModulateurSaisie(?TypeModulateurSaisieForm $formModulateurTypeModulateurSaisie)
    {
        $this->formModulateurTypeModulateurSaisie = $formModulateurTypeModulateurSaisie;

        return $this;
    }



    public function getFormModulateurTypeModulateurSaisie(): ?TypeModulateurSaisieForm
    {
        if (!empty($this->formModulateurTypeModulateurSaisie)) {
            return $this->formModulateurTypeModulateurSaisie;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(TypeModulateurSaisieForm::class);
    }
}