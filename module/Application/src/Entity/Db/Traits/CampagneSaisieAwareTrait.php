<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\CampagneSaisie;

/**
 * Description of CampagneSaisieAwareTrait
 *
 * @author UnicaenCode
 */
trait CampagneSaisieAwareTrait
{
    /**
     * @var CampagneSaisie
     */
    private $campagneSaisie;





    /**
     * @param CampagneSaisie $campagneSaisie
     * @return self
     */
    public function setCampagneSaisie( CampagneSaisie $campagneSaisie = null )
    {
        $this->campagneSaisie = $campagneSaisie;
        return $this;
    }



    /**
     * @return CampagneSaisie
     */
    public function getCampagneSaisie()
    {
        return $this->campagneSaisie;
    }
}