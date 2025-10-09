<?php

namespace OffreFormation\Form\EtapeCentreCout\Traits;

use OffreFormation\Form\EtapeCentreCout\EtapeCentreCoutForm;

/**
 * Description of EtapeCentreCoutFormAwareTrait
 *
 * @author UnicaenCode
 */
trait EtapeCentreCoutFormAwareTrait
{
    protected ?EtapeCentreCoutForm $formOffreFormationEtapeCentreCoutEtapeCentreCout = null;



    /**
     * @param EtapeCentreCoutForm $formOffreFormationEtapeCentreCoutEtapeCentreCout
     *
     * @return self
     */
    public function setFormOffreFormationEtapeCentreCoutEtapeCentreCout(?EtapeCentreCoutForm $formOffreFormationEtapeCentreCoutEtapeCentreCout)
    {
        $this->formOffreFormationEtapeCentreCoutEtapeCentreCout = $formOffreFormationEtapeCentreCoutEtapeCentreCout;

        return $this;
    }



    public function getFormOffreFormationEtapeCentreCoutEtapeCentreCout(): ?EtapeCentreCoutForm
    {
        if (!empty($this->formOffreFormationEtapeCentreCoutEtapeCentreCout)) {
            return $this->formOffreFormationEtapeCentreCoutEtapeCentreCout;
        }

        return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(EtapeCentreCoutForm::class);
    }
}