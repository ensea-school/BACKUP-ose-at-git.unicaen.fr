<?php

namespace Application\Service\Interfaces;

use Application\Service\DisciplineService;
use RuntimeException;

/**
 * Description of DisciplineServiceAwareInterface
 *
 * @author UnicaenCode
 */
interface DisciplineServiceAwareInterface
{
    /**
     * @param DisciplineService $serviceDiscipline
     * @return self
     */
    public function setServiceDiscipline( DisciplineService $serviceDiscipline );



    /**
     * @return DisciplineServiceAwareInterface
     * @throws RuntimeException
     */
    public function getServiceDiscipline();
}