<?php

namespace Application\Service\Interfaces;

use Application\Service\ModificationServiceDu;
use RuntimeException;

/**
 * Description of ModificationServiceDuAwareInterface
 *
 * @author UnicaenCode
 */
interface ModificationServiceDuAwareInterface
{
    /**
     * @param ModificationServiceDu $serviceModificationServiceDu
     * @return self
     */
    public function setServiceModificationServiceDu( ModificationServiceDu $serviceModificationServiceDu );



    /**
     * @return ModificationServiceDuAwareInterface
     * @throws RuntimeException
     */
    public function getServiceModificationServiceDu();
}