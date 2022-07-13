<?php

namespace Service\Entity\Db;

/**
 * Description of CampagneSaisieAwareTrait
 *
 * @author UnicaenCode
 */
trait CampagneSaisieAwareTrait
{
    protected ?CampagneSaisie $campagneSaisie = null;



    /**
     * @param CampagneSaisie $campagneSaisie
     *
     * @return self
     */
    public function setCampagneSaisie(?CampagneSaisie $campagneSaisie)
    {
        $this->campagneSaisie = $campagneSaisie;

        return $this;
    }



    public function getCampagneSaisie(): ?CampagneSaisie
    {
        return $this->campagneSaisie;
    }
}