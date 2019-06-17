<?php

namespace BddAdmin;


trait BddAwareTrait
{
    /**
     * @var Bdd
     */
    private $bdd;



    /**
     * @return Bdd|null
     */
    public function getBdd() //: ?Bdd
    {
        return $this->bdd;
    }



    /**
     * @param Bdd $bdd
     *
     * @return self
     */
    public function setBdd(Bdd $bdd): self
    {
        $this->bdd = $bdd;

        return $this;
    }
}