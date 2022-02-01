<?php

namespace Application\Form\OffreFormation\Traits;

use Application\Form\OffreFormation\VolumeHoraireEnsForm;

/**
 * Description of VolumeHoraireEnsFormAwareTrait
 *
 * @author UnicaenCode
 */
trait VolumeHoraireEnsFormAwareTrait
{
    protected ?VolumeHoraireEnsForm $formOffreFormationVolumeHoraireEns;



    /**
     * @param VolumeHoraireEnsForm|null $formOffreFormationVolumeHoraireEns
     *
     * @return self
     */
    public function setFormOffreFormationVolumeHoraireEns( ?VolumeHoraireEnsForm $formOffreFormationVolumeHoraireEns )
    {
        $this->formOffreFormationVolumeHoraireEns = $formOffreFormationVolumeHoraireEns;

        return $this;
    }



    public function getFormOffreFormationVolumeHoraireEns(): ?VolumeHoraireEnsForm
    {
        if (!$this->formOffreFormationVolumeHoraireEns){
            $this->formOffreFormationVolumeHoraireEns = \Application::$container->get('FormElementManager')->get(VolumeHoraireEnsForm::class);
        }

        return $this->formOffreFormationVolumeHoraireEns;
    }
}