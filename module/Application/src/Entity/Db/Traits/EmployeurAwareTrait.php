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
    /**
     * @var Employeur|null
     */
    private $employeur;



    /**
     * @param Employeur|null $employeur
     *
     * @return self
     */
    public function setEmployeur(?Employeur $employeur = null)
    {
        $this->employeur = $employeur;

        return $this;
    }



    /**
     * @return Employeur|null
     */
    public function getEmployeur(): ?Employeur
    {
        return $this->employeur;
    }
}