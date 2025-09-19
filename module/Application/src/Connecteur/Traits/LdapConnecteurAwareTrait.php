<?php

namespace Application\Connecteur\Traits;

use Application\Connecteur\LdapConnecteur;

/**
 * Description of LdapConnecteurAwareTrait
 *
 * @author UnicaenCode
 */
trait LdapConnecteurAwareTrait
{
    protected ?LdapConnecteur $connecteurLdap = null;



    /**
     * @param LdapConnecteur $connecteurLdap
     *
     * @return self
     */
    public function setConnecteurLdap(?LdapConnecteur $connecteurLdap)
    {
        $this->connecteurLdap = $connecteurLdap;

        return $this;
    }



    public function getConnecteurLdap(): ?LdapConnecteur
    {
        if (empty($this->connecteurLdap)) {
            $this->connecteurLdap = \Framework\Application\Application::getInstance()->container()->get(LdapConnecteur::class);
        }

        return $this->connecteurLdap;
    }
}