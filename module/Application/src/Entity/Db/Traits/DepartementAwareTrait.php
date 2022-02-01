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
    protected ?Departement $departement;



    /**
     * @param Departement|null $departement
     *
     * @return self
     */
    public function setDepartement( ?Departement $departement )
    {
        $this->departement = $departement;

        return $this;
    }



    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }
}