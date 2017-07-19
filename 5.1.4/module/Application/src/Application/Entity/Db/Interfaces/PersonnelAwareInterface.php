<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Personnel;

/**
 * Description of PersonnelAwareInterface
 *
 * @author UnicaenCode
 */
interface PersonnelAwareInterface
{
    /**
     * @param Personnel $personnel
     * @return self
     */
    public function setPersonnel( Personnel $personnel = null );



    /**
     * @return Personnel
     */
    public function getPersonnel();
}