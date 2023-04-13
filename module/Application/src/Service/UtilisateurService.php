<?php

namespace Application\Service;

use Application\Connecteur\Traits\LdapConnecteurAwareTrait;
use Application\Entity\Db\Utilisateur;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
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
    use UserServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use WorkflowServiceAwareTrait;


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

        $result = array_merge($locaUsers, $ldapUsers);

        uasort($result, function ($a, $b) {
            return $a['label'] > $b['label'] ? 1 : 0;
        });

        return $result;
    }



    private function rechercheUtilisateursLocaux($critere)
    {
        $critere = Util::reduce($critere);

        $sql = "SELECT username, display_name FROM utilisateur WHERE OSE_DIVERS.STR_REDUCE(display_name) LIKE '%$critere%' ORDER BY display_name";

        $res = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql);

        $ul = [];
        foreach ($res as $r) {
            $ul[$r['USERNAME']] = [
                'id'    => $r['USERNAME'],
                'label' => $r['DISPLAY_NAME'],
            ];
        }

        return $ul;
    }



    /**
     * @param string    $nom
     * @param string    $prenom
     * @param \DateTime $dateNaissance
     * @param string    $login
     * @param string    $motDePasse
     * @param array     $params
     *
     * Params :
     *   creer-intervenant : bool
     *   code   : null | string                     => généré si non fourni
     *   annee  : null | int | Annee                => Année en cours si non fournie
     *   statut : null | string | Statut => AUTRES si non fourni, si string alors c'est le code du statut
     *
     * @return Utilisateur
     */
    public function creerUtilisateur(string $nom, string $prenom, \DateTime $dateNaissance, string $login, string $motDePasse, array $params = []): Utilisateur
    {
        if (!isset($params['creer-intervenant']) || empty($params['creer-intervenant'])) {
            $params['creer-intervenant'] = false;
        }

        $utilisateur = new Utilisateur();
        $utilisateur->setUsername($login);
        $utilisateur->setDisplayName($prenom . ' ' . $nom);
        $utilisateur->setState(1);
        if ($params['creer-intervenant']) {
            $intervenant = $this->getServiceIntervenant()->creerIntervenant($nom, $prenom, $dateNaissance, $params);
            $utilisateur->setCode($intervenant->getCode());
            $intervenant->setUtilisateurCode($intervenant->getCode());
            $this->getServiceIntervenant()->save($intervenant);
            $this->getServiceWorkflow()->calculerTableauxBord([], $intervenant);
        }
        $utilisateur->setPassword($motDePasse);
        $this->save($utilisateur);
        $this->changerMotDePasse($utilisateur, $motDePasse);

        return $utilisateur;
    }



    public function changerMotDePasse(Utilisateur $utilisateur, string $motDePasse)
    {
        if (strlen($motDePasse) < 6) {
            throw new \Exception("Mot de passe trop court : il doit faire au moint 6 caractères");
        }

        $this->userService->updateUserPassword($utilisateur, $motDePasse);
    }

}