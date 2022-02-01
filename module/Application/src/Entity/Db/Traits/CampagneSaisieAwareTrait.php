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
    protected ?CampagneSaisie $campagneSaisie;



    /**
     * @param CampagneSaisie|null $campagneSaisie
     *
     * @return self
     */
    public function setCampagneSaisie( ?CampagneSaisie $campagneSaisie )
    {
        $this->campagneSaisie = $campagneSaisie;

        return $this;
    }



    public function getCampagneSaisie(): ?CampagneSaisie
    {
        return $this->campagneSaisie;
    }
}