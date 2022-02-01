<?php

namespace ExportRh\Connecteur\Siham;


/**
 * Description of SihamConnecteurAwareTrait
 *
 * @author UnicaenCode
 */
trait SihamConnecteurAwareTrait
{
    protected ?SihamConnecteur $connecteurSihamSiham = null;



    /**
     * @param SihamConnecteur $connecteurSihamSiham
     *
     * @return self
     */
    public function setConnecteurSihamSiham( ?SihamConnecteur $connecteurSihamSiham )
    {
        $this->connecteurSihamSiham = $connecteurSihamSiham;

        return $this;
    }



    public function getConnecteurSihamSiham(): ?SihamConnecteur
    {
        return $this->connecteurSihamSiham;
    }
}