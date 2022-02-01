<?php

namespace Application\Connecteur\Bdd;


/**
 * Description of BddConnecteurAwareTrait
 *
 * @author UnicaenCode
 */
trait BddConnecteurAwareTrait
{
    protected ?BddConnecteur $connecteurBddBdd;



    /**
     * @param BddConnecteur|null $connecteurBddBdd
     *
     * @return self
     */
    public function setConnecteurBddBdd( ?BddConnecteur $connecteurBddBdd )
    {
        $this->connecteurBddBdd = $connecteurBddBdd;

        return $this;
    }



    public function getConnecteurBddBdd(): ?BddConnecteur
    {
        return $this->connecteurBddBdd;
    }
}