<?php

namespace OffreFormation\Form\Traits;

use OffreFormation\Form\VolumeHoraireEnsForm;

/**
 * Description of VolumeHoraireEnsFormAwareTrait
 *
 * @author UnicaenCode
 */
trait VolumeHoraireEnsFormAwareTrait
{
    protected ?VolumeHoraireEnsForm $formOffreFormationVolumeHoraireEns = null;



    /**
     * @param VolumeHoraireEnsForm $formOffreFormationVolumeHoraireEns
     *
     * @return self
     */
    public function setFormOffreFormationVolumeHoraireEns(?VolumeHoraireEnsForm $formOffreFormationVolumeHoraireEns)
    {
        $this->formOffreFormationVolumeHoraireEns = $formOffreFormationVolumeHoraireEns;

        return $this;
    }



    public function getFormOffreFormationVolumeHoraireEns(): ?VolumeHoraireEnsForm
    {
        if (!empty($this->formOffreFormationVolumeHoraireEns)) {
            return $this->formOffreFormationVolumeHoraireEns;
        }

        return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(VolumeHoraireEnsForm::class);
    }
}