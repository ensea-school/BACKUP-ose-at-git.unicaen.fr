<?php

namespace Application\Form\Service\Interfaces;

use Application\Form\Service\SaisieFieldset;

/**
 * Description of SaisieFieldsetAwareInterface
 *
 * @author UnicaenCode
 */
interface SaisieFieldsetAwareInterface
{
    /**
     * @param SaisieFieldset|null $formServiceSaisieFieldset
     *
     * @return self
     */
    public function setFormServiceSaisieFieldset( SaisieFieldset $formServiceSaisieFieldset );



    public function getFormServiceSaisieFieldset(): ?SaisieFieldset;
}