<?php

namespace Application\Form\OffreFormation\Traits;

use Application\Form\OffreFormation\EtapeSaisie;

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

        return \Application::$container->get('FormElementManager')->get(EtapeSaisie::class);
    }
}