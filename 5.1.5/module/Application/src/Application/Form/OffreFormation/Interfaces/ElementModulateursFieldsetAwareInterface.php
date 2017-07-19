<?php

namespace Application\Form\OffreFormation\Interfaces;

use Application\Form\OffreFormation\ElementModulateursFieldset;
use RuntimeException;

/**
 * Description of ElementModulateursFieldsetAwareInterface
 *
 * @author UnicaenCode
 */
interface ElementModulateursFieldsetAwareInterface
{
    /**
     * @param ElementModulateursFieldset $fieldsetOffreFormationElementModulateurs
     * @return self
     */
    public function setFieldsetOffreFormationElementModulateurs( ElementModulateursFieldset $fieldsetOffreFormationElementModulateurs );



    /**
     * @return ElementModulateursFieldsetAwareInterface
     * @throws RuntimeException
     */
    public function getFieldsetOffreFormationElementModulateurs();
}