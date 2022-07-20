<?php

namespace Application\Form\ServiceReferentiel\Interfaces;

use Application\Form\ServiceReferentiel\Saisie;

/**
 * Description of SaisieAwareInterface
 *
 * @author UnicaenCode
 */
interface SaisieAwareInterface
{
    /**
     * @param Saisie|null $formServiceReferentielSaisie
     *
     * @return self
     */
    public function setFormServiceReferentielSaisie( ?Saisie $formServiceReferentielSaisie );



    public function getFormServiceReferentielSaisie(): ?Saisie;
}