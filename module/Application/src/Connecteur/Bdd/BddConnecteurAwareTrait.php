<?php

namespace Application\Connecteur\Bdd;


trait BddConnecteurAwareTrait
{
    /**
     * @var BddConnecteur
     */
    private $bdd;



    public function getBdd(): BddConnecteur
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