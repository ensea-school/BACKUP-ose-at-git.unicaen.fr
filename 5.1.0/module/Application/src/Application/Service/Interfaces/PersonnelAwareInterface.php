<?php

namespace Application\Service\Interfaces;

use Application\Service\Personnel;
use RuntimeException;

/**
 * Description of PersonnelAwareInterface
 *
 * @author UnicaenCode
 */
interface PersonnelAwareInterface
{
    /**
     * @param Personnel $servicePersonnel
     * @return self
     */
    public function setServicePersonnel( Personnel $servicePersonnel );



    /**
     * @return PersonnelAwareInterface
     * @throws RuntimeException
     */
    public function getServicePersonnel();
}