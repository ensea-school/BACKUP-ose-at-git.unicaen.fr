<?php

namespace Application\Connecteur\Bdd;


trait BddConnecteurAwareTrait
{
    /**
     * @var BddConnecteur
     */
    private $bdd;



    /**
     * @return BddConnecteur
     */
    public function getBdd()
    {
        return $this->bdd;
    }



    /**
     * @param BddConnecteur $bdd
     *
     * @return BddConnecteurAwareTrait
     */
    public function setBdd($bdd)
    {
        $this->bdd = $bdd;

        return $this;
    }

}