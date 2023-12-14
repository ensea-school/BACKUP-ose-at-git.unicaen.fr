<?php

namespace Paiement\Form\CentreCout;


/**
 * Description of CentreCoutStructureSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait CentreCoutStructureSaisieFormAwareTrait
{
    protected ?CentreCoutStructureSaisieForm $formCentreCoutCentreCoutStructureSaisie = null;



    /**
     * @param CentreCoutStructureSaisieForm $formCentreCoutCentreCoutStructureSaisie
     *
     * @return self
     */
    public function setFormCentreCoutCentreCoutStructureSaisie(?CentreCoutStructureSaisieForm $formCentreCoutCentreCoutStructureSaisie)
    {
        $this->formCentreCoutCentreCoutStructureSaisie = $formCentreCoutCentreCoutStructureSaisie;

        return $this;
    }



    public function getFormCentreCoutCentreCoutStructureSaisie(): ?CentreCoutStructureSaisieForm
    {
        if (!empty($this->formCentreCoutCentreCoutStructureSaisie)) {
            return $this->formCentreCoutCentreCoutStructureSaisie;
        }

        return \OseAdmin::instance()->container()->get('FormElementManager')->get(CentreCoutStructureSaisieForm::class);
    }
}