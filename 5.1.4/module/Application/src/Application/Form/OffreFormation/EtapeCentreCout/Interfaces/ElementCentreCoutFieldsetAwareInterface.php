<?php

namespace Application\Form\OffreFormation\EtapeCentreCout\Interfaces;

use Application\Form\OffreFormation\EtapeCentreCout\ElementCentreCoutFieldset;
use RuntimeException;

/**
 * Description of ElementCentreCoutFieldsetAwareInterface
 *
 * @author UnicaenCode
 */
interface ElementCentreCoutFieldsetAwareInterface
{
    /**
     * @param ElementCentreCoutFieldset $fieldsetOffreFormationEtapeCentreCoutElementCentreCout
     * @return self
     */
    public function setFieldsetOffreFormationEtapeCentreCoutElementCentreCout( ElementCentreCoutFieldset $fieldsetOffreFormationEtapeCentreCoutElementCentreCout );



    /**
     * @return ElementCentreCoutFieldsetAwareInterface
     * @throws RuntimeException
     */
    public function getFieldsetOffreFormationEtapeCentreCoutElementCentreCout();
}