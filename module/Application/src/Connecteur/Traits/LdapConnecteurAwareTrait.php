<?php

namespace Application\Connecteur\Traits;

use Application\Connecteur\LdapConnecteur;

/**
 * Description of LdapConnecteurAwareTrait
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
trait LdapConnecteurAwareTrait
{
    /**
     * @var LdapConnecteur
     */
    private $connecteurLdap;



    /**
     * @param LdapConnecteur $connecteurLdap
     *
     * @return self
     */
    public function setConnecteurLdap(LdapConnecteur $connecteurLdap)
    {
        $this->connecteurLdap = $connecteurLdap;

        return $this;
    }



    public function getConnecteurLdap(): LdapConnecteur
    {
        if (empty($this->connecteurLdap)) {
            $this->connecteurLdap = \Application::$container->get(LdapConnecteur::class);
        }

        return $this->connecteurLdap;
    }
}