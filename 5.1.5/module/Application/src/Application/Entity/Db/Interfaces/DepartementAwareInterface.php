<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Departement;

/**
 * Description of DepartementAwareInterface
 *
 * @author UnicaenCode
 */
interface DepartementAwareInterface
{
    /**
     * @param Departement $departement
     * @return self
     */
    public function setDepartement( Departement $departement = null );



    /**
     * @return Departement
     */
    public function getDepartement();
}