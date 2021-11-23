<?php

namespace Application\Form\Intervenant\Interfaces;

use Application\Form\Intervenant\MotifModificationServiceDuFieldset;
use RuntimeException;

/**
 * Description of MotifModificationServiceDuFieldsetAwareInterface
 *
 * @author UnicaenCode
 */
interface MotifModificationServiceDuFieldsetAwareInterface
{
    /**
     * @param MotifModificationServiceDuFieldset $fieldsetIntervenantMotifModificationServiceDu
     * @return self
     */
    public function setFieldsetIntervenantMotifModificationServiceDu( MotifModificationServiceDuFieldset $fieldsetIntervenantMotifModificationServiceDu );



    /**
     * @return MotifModificationServiceDuFieldsetAwareInterface
     * @throws RuntimeException
     */
    public function getFieldsetIntervenantMotifModificationServiceDu();
}