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
    /**
     * @var Pays
     */
    private $pays;





    /**
     * @param Pays $pays
     * @return self
     */
    public function setPays( Pays $pays = null )
    {
        $this->pays = $pays;
        return $this;
    }



    /**
     * @return Pays
     */
    public function getPays()
    {
        return $this->pays;
    }
}