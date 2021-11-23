<?php

namespace Application\Form\Intervenant\Interfaces;

use Application\Form\Intervenant\ModificationServiceDuFieldset;
use RuntimeException;

/**
 * Description of ModificationServiceDuFieldsetAwareInterface
 *
 * @author UnicaenCode
 */
interface ModificationServiceDuFieldsetAwareInterface
{
    /**
     * @param ModificationServiceDuFieldset $fieldsetIntervenantModificationServiceDu
     * @return self
     */
    public function setFieldsetIntervenantModificationServiceDu( ModificationServiceDuFieldset $fieldsetIntervenantModificationServiceDu );



    /**
     * @return ModificationServiceDuFieldsetAwareInterface
     * @throws RuntimeException
     */
    public function getFieldsetIntervenantModificationServiceDu();
}