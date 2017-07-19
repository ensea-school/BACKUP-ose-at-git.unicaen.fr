<?php

namespace Application\Service\Interfaces;

use Application\Service\MotifModificationServiceDu;
use RuntimeException;

/**
 * Description of MotifModificationServiceDuAwareInterface
 *
 * @author UnicaenCode
 */
interface MotifModificationServiceDuAwareInterface
{
    /**
     * @param MotifModificationServiceDu $serviceMotifModificationServiceDu
     * @return self
     */
    public function setServiceMotifModificationServiceDu( MotifModificationServiceDu $serviceMotifModificationServiceDu );



    /**
     * @return MotifModificationServiceDuAwareInterface
     * @throws RuntimeException
     */
    public function getServiceMotifModificationServiceDu();
}