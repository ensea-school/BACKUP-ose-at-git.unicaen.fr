<?php

namespace Application\Form\VolumeHoraireReferentiel\Interfaces;

use Application\Form\VolumeHoraireReferentiel\Saisie;
use RuntimeException;

/**
 * Description of SaisieAwareInterface
 *
 * @author UnicaenCode
 */
interface SaisieAwareInterface
{
    /**
     * @param Saisie $formVolumeHoraireReferentielSaisie
     * @return self
     */
    public function setFormVolumeHoraireReferentielSaisie( Saisie $formVolumeHoraireReferentielSaisie );



    /**
     * @return SaisieAwareInterface
     * @throws RuntimeException
     */
    public function getFormVolumeHoraireReferentielSaisie();
}