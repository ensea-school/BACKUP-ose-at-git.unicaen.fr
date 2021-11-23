<?php

namespace Application\Form\ServiceReferentiel\Interfaces;

use Application\Form\ServiceReferentiel\Saisie;
use RuntimeException;

/**
 * Description of SaisieAwareInterface
 *
 * @author UnicaenCode
 */
interface SaisieAwareInterface
{
    /**
     * @param Saisie $formServiceReferentielSaisie
     * @return self
     */
    public function setFormServiceReferentielSaisie( Saisie $formServiceReferentielSaisie );



    /**
     * @return SaisieAwareInterface
     * @throws RuntimeException
     */
    public function getFormServiceReferentielSaisie();
}