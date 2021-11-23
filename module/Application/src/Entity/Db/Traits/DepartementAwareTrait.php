<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Departement;

/**
 * Description of DepartementAwareTrait
 *
 * @author UnicaenCode
 */
trait DepartementAwareTrait
{
    /**
     * @var Departement
     */
    private $departement;





    /**
     * @param Departement $departement
     * @return self
     */
    public function setDepartement( Departement $departement = null )
    {
        $this->departement = $departement;
        return $this;
    }



    /**
     * @return Departement
     */
    public function getDepartement()
    {
        return $this->departement;
    }
}