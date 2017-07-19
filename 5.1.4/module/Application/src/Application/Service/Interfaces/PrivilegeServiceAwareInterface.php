<?php

namespace Application\Service\Interfaces;

use Application\Service\PrivilegeService;
use RuntimeException;

/**
 * Description of PrivilegeServiceAwareInterface
 *
 * @author UnicaenCode
 */
interface PrivilegeServiceAwareInterface
{
    /**
     * @param PrivilegeService $servicePrivilege
     * @return self
     */
    public function setServicePrivilege( PrivilegeService $servicePrivilege );



    /**
     * @return PrivilegeServiceAwareInterface
     * @throws RuntimeException
     */
    public function getServicePrivilege();
}