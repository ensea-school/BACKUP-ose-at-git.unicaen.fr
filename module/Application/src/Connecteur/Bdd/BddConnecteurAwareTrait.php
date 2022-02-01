<?php

namespace Application\Connecteur\Bdd;


/**
 * Description of BddConnecteurAwareTrait
 *
 * @author UnicaenCode
 */
trait BddConnecteurAwareTrait
{
    protected ?BddConnecteur $connecteurBddBdd = null;



    /**
     * @param BddConnecteur $connecteurBddBdd
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