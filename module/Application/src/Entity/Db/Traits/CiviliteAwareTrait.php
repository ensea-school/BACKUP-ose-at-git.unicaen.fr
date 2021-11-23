<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Civilite;

/**
 * Description of CiviliteAwareTrait
 *
 * @author UnicaenCode
 */
trait CiviliteAwareTrait
{
    /**
     * @var Civilite|null
     */
    private $civilite;



    /**
     * @return Civilite|null
     */
    public function getCivilite(): ?Civilite
    {
        return $this->civilite;
    }



    /**
     * @param Civilite|null $civilite
     *
     * @return self
     */
    public function setCivilite(?Civilite $civilite): self
    {
        $this->civilite = $civilite;

        return $this;
    }

}