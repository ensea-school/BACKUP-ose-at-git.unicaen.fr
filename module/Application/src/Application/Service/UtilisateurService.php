<?php

namespace Application\Service;

use Application\Connecteur\Traits\LdapConnecteurAwareTrait;
use Application\Entity\Db\Utilisateur;
use Application\Service\Traits\ParametresServiceAwareTrait;
use UnicaenApp\Util;

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
     * Retourne l'utilisateur OSE
     *
     * @return Utilisateur
     */
    public function getOse()
    {
        $oseUserId = $this->getServiceParametres()->get('oseuser');

        return $this->get($oseUserId);
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



    /**
     * @param string $critere
     *
     * @return array
     */
    public function rechercheUtilisateurs($critere)
    {
        /* Ajouter les utilisateurs locaux à la recherche... */
        $ldapUsers = @$this->getConnecteurLdap()->rechercheUtilisateurs($critere);
        $locaUsers = $this->rechercheUtilisateursLocaux($critere);

        $result = array_merge($locaUsers, $ldapUsers );

        uasort($result, function($a,$b){
           return $a['label'] > $b['label'];
        });

        return $result;
    }



    private function rechercheUtilisateursLocaux($critere)
    {
        $critere = Util::reduce($critere);

        $sql = "SELECT username, display_name FROM utilisateur WHERE OSE_DIVERS.STR_REDUCE(display_name) LIKE '%$critere%' ORDER BY display_name";

        $res = $this->getEntityManager()->getConnection()->fetchAll($sql);

        $ul = [];
        foreach( $res as $r ){
            $ul[$r['USERNAME']] = [
                'id' => $r['USERNAME'],
                'label' => $r['DISPLAY_NAME'],
            ];
        }

        return $ul;
    }

}