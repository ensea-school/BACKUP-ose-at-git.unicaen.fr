<?php

namespace Paiement\Form\Modulateur;


/**
 * Description of TypeModulateurStructureSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeModulateurStructureSaisieFormAwareTrait
{
    protected ?TypeModulateurStructureSaisieForm $formModulateurTypeModulateurStructureSaisie = null;



    /**
     * @param TypeModulateurStructureSaisieForm $formModulateurTypeModulateurStructureSaisie
     *
     * @return self
     */
    public function setFormModulateurTypeModulateurStructureSaisie(?TypeModulateurStructureSaisieForm $formModulateurTypeModulateurStructureSaisie)
    {
        $this->formModulateurTypeModulateurStructureSaisie = $formModulateurTypeModulateurStructureSaisie;

        return $this;
    }



    public function getFormModulateurTypeModulateurStructureSaisie(): ?TypeModulateurStructureSaisieForm
    {
        if (!empty($this->formModulateurTypeModulateurStructureSaisie)) {
            return $this->formModulateurTypeModulateurStructureSaisie;
        }

        return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(TypeModulateurStructureSaisieForm::class);
    }
}