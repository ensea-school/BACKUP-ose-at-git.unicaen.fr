<?php

namespace Application\Service\Interfaces;

use Application\Service\Privilege;
use RuntimeException;

/**
 * Description of PrivilegeAwareInterface
 *
 * @author UnicaenCode
 */
interface PrivilegeAwareInterface
{
    /**
     * @param Privilege $servicePrivilege
     * @return self
     */
    public function setServicePrivilege( Privilege $servicePrivilege );



    /**
     * @return PrivilegeAwareInterface
     * @throws RuntimeException
     */
    public function getServicePrivilege();
}