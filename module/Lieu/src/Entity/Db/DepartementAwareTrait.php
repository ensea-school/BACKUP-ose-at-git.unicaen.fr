<?php

namespace Lieu\Entity\Db;

/**
 * Description of DepartementAwareTrait
 *
 * @author UnicaenCode
 */
trait DepartementAwareTrait
{
    protected ?Departement $departement = null;



    /**
     * @param  $departement
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