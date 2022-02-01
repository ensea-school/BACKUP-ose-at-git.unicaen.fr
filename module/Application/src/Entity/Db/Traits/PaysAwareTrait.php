<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Pays;

/**
 * Description of PaysAwareTrait
 *
 * @author UnicaenCode
 */
trait PaysAwareTrait
{
    protected ?Pays $pays = null;



    /**
     * @param Pays $pays
     *
     * @return self
     */
    public function setPays( Pays $pays )
    {
        $this->pays = $pays;

        return $this;
    }



    public function getPays(): ?Pays
    {
        return $this->pays;
    }
}