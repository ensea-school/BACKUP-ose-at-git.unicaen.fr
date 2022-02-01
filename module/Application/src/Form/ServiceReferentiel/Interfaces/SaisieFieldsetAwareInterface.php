<?php

namespace Application\Form\ServiceReferentiel\Interfaces;

use Application\Form\ServiceReferentiel\SaisieFieldset;

/**
 * Description of SaisieFieldsetAwareInterface
 *
 * @author UnicaenCode
 */
interface SaisieFieldsetAwareInterface
{
    /**
     * @param SaisieFieldset|null $formServiceReferentielSaisieFieldset
     *
     * @return self
     */
    public function setFormServiceReferentielSaisieFieldset( SaisieFieldset $formServiceReferentielSaisieFieldset );



    public function getFormServiceReferentielSaisieFieldset(): ?SaisieFieldset;
}