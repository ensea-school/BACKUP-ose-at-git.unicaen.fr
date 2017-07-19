<?php

namespace Application\Form\ServiceReferentiel\Interfaces;

use Application\Form\ServiceReferentiel\SaisieFieldset;
use RuntimeException;

/**
 * Description of SaisieFieldsetAwareInterface
 *
 * @author UnicaenCode
 */
interface SaisieFieldsetAwareInterface
{
    /**
     * @param SaisieFieldset $fieldsetServiceReferentielSaisie
     * @return self
     */
    public function setFieldsetServiceReferentielSaisie( SaisieFieldset $fieldsetServiceReferentielSaisie );



    /**
     * @return SaisieFieldsetAwareInterface
     * @throws RuntimeException
     */
    public function getFieldsetServiceReferentielSaisie();
}