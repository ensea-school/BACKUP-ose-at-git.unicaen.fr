<?php

namespace Utilisateur\Service;

use Administration\Service\ParametresServiceAwareTrait;
use Application\Service\AbstractEntityService;
use RuntimeException;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Lieu\Entity\Db\Structure;
use UnicaenApp\Util;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Utilisateur\Connecteur\LdapConnecteurAwareTrait;
use Utilisateur\Entity\Db\Role;
use Utilisateur\Entity\Db\Utilisateur;
use Workflow\Service\WorkflowServiceAwareTrait;

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



    public function getUtilisateursByRole(Role $role)
    {

        $sql = '
            SELECT * FROM affectation a
            JOIN UTILISATEUR u ON a.UTILISATEUR_ID = u.ID 
            WHERE a.HISTO_DESTRUCTION IS NULL
            AND a.role_id = ' . $role->getId();

        $res = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql);

        return $res;

    }



    public function getUtilisateursByRoleAndStructure(Role $role, Structure $structure)
    {
        //On prend les utilisateurs de la structure et du role donnée ainsi que les utilisateurs ayant le même rôle dans les structures hiérarchique
        if ($structure instanceof Structure) {
            $ids = $structure->getIdsArray();
            $ids = implode(',', $ids);

            $sql = '
            SELECT * FROM affectation a
            JOIN UTILISATEUR u ON a.UTILISATEUR_ID = u.ID 
            JOIN structure s ON a.structure_id = s.ID
            WHERE a.HISTO_DESTRUCTION IS NULL
            AND a.structure_id IN (' . $ids . ')
            AND a.role_id = ' . $role->getId();
        } else {
            throw new \Exception("Structure fournie non valide.");
        }

        $res = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql);
        return $res;

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
        $utilisateur->setPassword($motDePasse, true);
        $this->save($utilisateur);

        return $utilisateur;
    }



    public function changerMotDePasse(Utilisateur $utilisateur, string $motDePasse)
    {
        if (strlen($motDePasse) < 6) {
            throw new \Exception("Mot de passe trop court : il doit faire au moins 6 caractères");
        }

        $utilisateur->setPassword($motDePasse, true);
        $this->save($utilisateur);
    }

}