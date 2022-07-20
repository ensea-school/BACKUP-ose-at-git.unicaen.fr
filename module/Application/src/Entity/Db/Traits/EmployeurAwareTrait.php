<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Employeur;

/**
 * Description of EmployeurAwareTrait
 *
 * @author UnicaenCode
 */
trait EmployeurAwareTrait
{
    protected ?Employeur $employeur = null;



    /**
     * @param Employeur $employeur
     *
     * @return self
     */
    public function setEmployeur( ?Employeur $employeur )
    {
        $this->employeur = $employeur;

        return $this;
    }



    public function getEmployeur(): ?Employeur
    {
        return $this->employeur;
    }
}