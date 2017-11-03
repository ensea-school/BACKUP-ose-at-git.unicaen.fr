<?php

namespace Application\Form\OffreFormation\Traits;

use Application\Form\OffreFormation\VolumeHoraireEnsForm;
use RuntimeException;

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
    public function setFormOffreFormationVolumeHoraireEns( VolumeHoraireEnsForm $formOffreFormationVolumeHoraireEns )
    {
        $this->formOffreFormationVolumeHoraireEns = $formOffreFormationVolumeHoraireEns;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return VolumeHoraireEnsForm
     * @throws RuntimeException
     */
    public function getFormOffreFormationVolumeHoraireEns()
    {
        return $this->formOffreFormationVolumeHoraireEns;
    }
}