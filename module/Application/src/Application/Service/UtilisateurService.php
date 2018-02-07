<?php

namespace Application\Service;

use Application\Connecteur\Traits\LdapConnecteurAwareTrait;
use Application\Entity\Db\Utilisateur;
use Application\Service\Traits\ParametresServiceAwareTrait;

/**
 * Description of Utilisateur
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class UtilisateurService extends AbstractEntityService
{
    use ParametresServiceAwareTrait;
    use LdapConnecteurAwareTrait;



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return Utilisateur::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'utilisateur';
    }



    /**
     * Retourne le directeur des ressources humaines, s'il est défini.
     *
     * @return Utilisateur
     */
    public function getDrh()
    {
        $drh = $this->getServiceParametres()->get('directeur_ressources_humaines_id');

        return $this->getByUsername($drh);
    }



    /**
     * @param $username
     *
     * @return Utilisateur
     */
    public function getByUsername($username)
    {
        return $this->getConnecteurLdap()->getUtilisateur($username);
    }
}