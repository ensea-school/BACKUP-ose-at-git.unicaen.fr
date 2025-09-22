<?php

namespace OffreFormation\Form\Traits;

use OffreFormation\Form\EtapeModulateursSaisie;

/**
 * Description of EtapeModulateursSaisieAwareTrait
 *
 * @author UnicaenCode
 */
trait EtapeModulateursSaisieAwareTrait
{
    protected ?EtapeModulateursSaisie $formOffreFormationEtapeModulateursSaisie = null;



    /**
     * @param EtapeModulateursSaisie $formOffreFormationEtapeModulateursSaisie
     *
     * @return self
     */
    public function setFormOffreFormationEtapeModulateursSaisie(?EtapeModulateursSaisie $formOffreFormationEtapeModulateursSaisie)
    {
        $this->formOffreFormationEtapeModulateursSaisie = $formOffreFormationEtapeModulateursSaisie;

        return $this;
    }



    public function getFormOffreFormationEtapeModulateursSaisie(): ?EtapeModulateursSaisie
    {
        if (!empty($this->formOffreFormationEtapeModulateursSaisie)) {
            return $this->formOffreFormationEtapeModulateursSaisie;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(EtapeModulateursSaisie::class);
    }
}