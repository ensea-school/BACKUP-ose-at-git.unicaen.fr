<?php

namespace Application\Service\Interfaces;

use Application\Service\Departement;
use RuntimeException;

/**
 * Description of DepartementAwareInterface
 *
 * @author UnicaenCode
 */
interface DepartementAwareInterface
{
    /**
     * @param Departement $serviceDepartement
     * @return self
     */
    public function setServiceDepartement( Departement $serviceDepartement );



    /**
     * @return DepartementAwareInterface
     * @throws RuntimeException
     */
    public function getServiceDepartement();
}