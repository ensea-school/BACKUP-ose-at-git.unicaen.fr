<?php

namespace OffreFormation\Form\Traits;

use OffreFormation\Form\EtapeSaisie;

/**
 * Description of EtapeSaisieAwareTrait
 *
 * @author UnicaenCode
 */
trait EtapeSaisieAwareTrait
{
    protected ?EtapeSaisie $formOffreFormationEtapeSaisie = null;



    /**
     * @param EtapeSaisie $formOffreFormationEtapeSaisie
     *
     * @return self
     */
    public function setFormOffreFormationEtapeSaisie(?EtapeSaisie $formOffreFormationEtapeSaisie)
    {
        $this->formOffreFormationEtapeSaisie = $formOffreFormationEtapeSaisie;

        return $this;
    }



    public function getFormOffreFormationEtapeSaisie(): ?EtapeSaisie
    {
        if (!empty($this->formOffreFormationEtapeSaisie)) {
            return $this->formOffreFormationEtapeSaisie;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(EtapeSaisie::class);
    }
}