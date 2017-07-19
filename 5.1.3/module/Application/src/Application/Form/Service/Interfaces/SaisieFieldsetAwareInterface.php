<?php

namespace Application\Form\Service\Interfaces;

use Application\Form\Service\SaisieFieldset;
use RuntimeException;

/**
 * Description of SaisieFieldsetAwareInterface
 *
 * @author UnicaenCode
 */
interface SaisieFieldsetAwareInterface
{
    /**
     * @param SaisieFieldset $fieldsetServiceSaisie
     * @return self
     */
    public function setFieldsetServiceSaisie( SaisieFieldset $fieldsetServiceSaisie );



    /**
     * @return SaisieFieldsetAwareInterface
     * @throws RuntimeException
     */
    public function getFieldsetServiceSaisie();
}