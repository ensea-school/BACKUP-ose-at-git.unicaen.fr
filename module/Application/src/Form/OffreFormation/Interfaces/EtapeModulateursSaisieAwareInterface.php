<?php

namespace Application\Form\OffreFormation\Interfaces;

use Application\Form\OffreFormation\EtapeModulateursSaisie;
use RuntimeException;

/**
 * Description of EtapeModulateursSaisieAwareInterface
 *
 * @author UnicaenCode
 */
interface EtapeModulateursSaisieAwareInterface
{
    /**
     * @param EtapeModulateursSaisie $formOffreFormationEtapeModulateursSaisie
     * @return self
     */
    public function setFormOffreFormationEtapeModulateursSaisie( EtapeModulateursSaisie $formOffreFormationEtapeModulateursSaisie );



    /**
     * @return EtapeModulateursSaisieAwareInterface
     * @throws RuntimeException
     */
    public function getFormOffreFormationEtapeModulateursSaisie();
}