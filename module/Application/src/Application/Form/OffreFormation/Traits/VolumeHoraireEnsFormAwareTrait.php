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
    /**
     * @var VolumeHoraireEnsForm
     */
    protected $formOffreFormationVolumeHoraireEns;



    /**
     * @param VolumeHoraireEnsForm $formOffreFormationVolumeHoraireEns
     *
     * @return self
     */
    public function setFormOffreFormationVolumeHoraireEns(VolumeHoraireEnsForm $formOffreFormationVolumeHoraireEns)
    {
        $this->formOffreFormationVolumeHoraireEns = $formOffreFormationVolumeHoraireEns;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return VolumeHoraireEnsForm
     */
    public function getFormOffreFormationVolumeHoraireEns()
    {
        if (!empty($this->formOffreFormationVolumeHoraireEns)) {
            return $this->formOffreFormationVolumeHoraireEns;
        }

        return \Application::$container->get('FormElementManager')->get(VolumeHoraireEnsForm::class);
    }
}