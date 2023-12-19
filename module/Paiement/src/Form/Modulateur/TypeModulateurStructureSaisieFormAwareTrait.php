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

        return \OseAdmin::instance()->container()->get('FormElementManager')->get(TypeModulateurStructureSaisieForm::class);
    }
}