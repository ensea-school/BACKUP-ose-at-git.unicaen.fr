<?php

namespace Application\Connecteur;

use Application\Entity\Db\Composante;
use Application\Entity\Db\Utilisateur;
use Application\Service\AbstractService;
use Application\Service\Traits\UtilisateurServiceAwareTrait;
use Framework\Application\Application;
use Intervenant\Entity\Db\Intervenant;
use UnicaenApp\Entity\Ldap\AbstractEntity;
use UnicaenApp\Entity\Ldap\People;
use UnicaenApp\Mapper\Ldap\People as MapperPeople;
use UnicaenApp\Mapper\Ldap\Structure as MapperStructure;
use UnicaenAuthentification\Service\UserContext;
use UnicaenAuthentification\Service\UserMapper as MapperUser;


/**
 * Description of LdapConnecteur
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class LdapConnecteur extends AbstractService
{
    use UtilisateurServiceAwareTrait;

    /**
     * @var UserContext
     */
    protected $serviceUserContext;

    /**
     * @var MapperStructure
     */
    protected $mapperStructure;

    /**
     * @var MapperPeople
     */
    protected $mapperPeople;

    /**
     * @var MapperUser
     */
    protected $mapperUser;

    /**
     * @var string
     */
    private $utilisateurLogin;

    /**
     * @var string
     */
    private $utilisateurFiltre;

    /**
     * @var string
     */
    private $utilisateurCode;

    /**
     * @var string
     */
    private $utilisateurCodeFiltre = '';

    /**
     * @var string
     */
    private $utilisateurExtraMasque;

    /**
     * @var array
     */
    private $utilisateurExtraAttributes;



    /**
     * LdapConnecteur constructor.
     *
     * @param UserContext     $serviceUserContext
     * @param MapperStructure $mapperStructure
     * @param MapperPeople    $mapperPeople
     * @param MapperUser      $mapperUser
     */
    public function __construct(
        UserContext $serviceUserContext,
        MapperStructure $mapperStructure,
        MapperPeople $mapperPeople,
        MapperUser $mapperUser
    )
    {
        $this->serviceUserContext = $serviceUserContext;
        $this->mapperStructure    = $mapperStructure;
        $this->mapperPeople       = $mapperPeople;
        $this->mapperUser         = $mapperUser;
    }



    public function isActif(): bool
    {
        return Application::getInstance()->config()['ldap']['actif'] ?? true;
    }



    /**
     * @param string $critere
     *
     * @return array
     */
    public function rechercheUtilisateurs($critere)
    {
        $result = [];
        if (($username = $critere) && $this->isActif()) {
            $foundUsers = $this->mapperPeople->findAllByNameOrUsername($username, $this->getUtilisateurLogin(), $this->getUtilisateurFiltre());
            /* @var $foundUsers People[] */

            foreach ($foundUsers as $ldapPeople) {
                $id = $this->getPeopleAttribute($ldapPeople, $this->getUtilisateurLogin());

                $result[$id] = [
                    'id'    => $id,
                    'label' => $ldapPeople->getCn(),
                    'extra' => $this->getPeopleExtra($ldapPeople),
                ];
            }
        }

        return $result;
    }



    private function getPeopleExtra(AbstractEntity $people)
    {
        $masque = $this->getUtilisateurExtraMasque();
        $attrs  = $this->getUtilisateurExtraAttributes();

        $attrsVals = [];
        foreach ($attrs as $attr) {
            $attrsVals[$attr] = $this->getPeopleAttribute($people, $attr);
        }

        return vsprintf($masque, $attrsVals);
    }



    private function getPeopleAttribute(AbstractEntity $ldapPeople, string $attribute)
    {
        try {
            $val = $ldapPeople->getData(strtolower($attribute));
            if (is_array($val)) $val = implode(',', $val);
        } catch (\Exception $e) {
            $val = null;
        }

        return $val;
    }



    /**
     * Enregistre un people dans la BDD et retourne l'enregistrement correspondant. S'il existe déjà alors il est simplement
     * retourné...
     *
     * @param string  $login
     * @param boolean $autoInsert
     *
     * @return Utilisateur
     */
    public function getUtilisateur(string $login, bool $autoInsert = true): ?Utilisateur
    {
        if ($user = $this->getServiceUtilisateur()->getRepo()->findOneBy(['username' => $login])) return $user; // si on le trouve alors c'est OK
        if ($user = $this->mapperUser->findByUsername($login)) return $user; // si on le trouve alors c'est OK

        if ($people = $this->mapperPeople->findOneByUsername($login)) {
            $entity = new Utilisateur();
            $entity->setEmail($people->getMail());
            $entity->setDisplayName($people->getDisplayName());
            $entity->setPassword('ldap');
            $entity->setState(in_array('deactivated', ldap_explode_dn($people->getDn(), 1)) ? 0 : 1);
            $entity->setUsername($login);

            if ($autoInsert) {
                $this->mapperUser->insert($entity);
            }

            return $entity;
        } else {
            return null;
        }
    }



    /**
     * Enregistre un people dans la BDD et retourne l'enregistrement correspondant. S'il existe déjà alors il est simplement
     * retourné...
     *
     * @param string  $login
     * @param boolean $autoInsert
     *
     * @return Utilisateur
     */
    public function getUtilisateurFromCode(string $code, bool $autoInsert = true): ?Utilisateur
    {
        if ($user = $this->getServiceUtilisateur()->getRepo()->findOneBy(['code' => $code])) return $user; // si on le trouve alors c'est OK

        if ($this->isActif()) {
            $ldapUser = $this->mapperPeople->findOneByNoIndividu($code);
            if ($ldapUser) {
                $login = $this->getPeopleAttribute($ldapUser, $this->getUtilisateurLogin());

                return $this->getUtilisateur($login, $autoInsert);
            }
        }

        return null;
    }



    public function intervenantGetLogin(Intervenant $intervenant): ?string
    {
        $utilisateurCode = $intervenant->getUtilisateurCode();

        if (!$utilisateurCode) return null;

        /* @var $utilisateur Utilisateur */
        $utilisateur = $this->getServiceUtilisateur()->getRepo()->findOneBy(['code' => $utilisateurCode]);

        if ($utilisateur && $utilisateur->getUsername()) {
            return $utilisateur->getUsername();
        }

        if ($this->isActif()) {
            $ldapUser = $this->mapperPeople->findOneByNoIndividu($utilisateurCode);
            if ($ldapUser) {
                return $this->getPeopleAttribute($ldapUser, $this->getUtilisateurLogin());
            }
        }

        return null;
    }



    public function getCodeFromLogin(string $login): ?string
    {
        $code = null;

        $u = $this->getUtilisateur($login);
        if ($u) {
            $code = $u->getCode();
        }

        if (!$code && $this->isActif()) {
            $ldapUser = $this->mapperPeople->findOneByUsername($login);

            if (!$ldapUser) return null;
            $code = $this->getPeopleAttribute($ldapUser, $this->getUtilisateurCode());
        }

        return $code;
    }



    /**
     * @return Utilisateur
     */
    public function getUtilisateurCourant()
    {
        return $this->serviceUserContext->getDbUser();
    }



    public function getUtilisateurCourantCode(): ?string
    {
        $utilisateur = $this->getUtilisateurCourant();

        if (!$utilisateur) return null;

        if ($utilisateur->getCode()) return $utilisateur->getCode();

        if ($this->isActif()) {
            $ldapUser = $this->mapperPeople->findOneByUsername($utilisateur->getUsername());

            if (!$ldapUser) return null;

            return $this->getPeopleAttribute($ldapUser, $this->getUtilisateurCode());
        }

        return null;
    }



    /**
     * @return string
     */
    public function getUtilisateurLogin(): string
    {
        return $this->utilisateurLogin;
    }



    /**
     * @param string $utilisateurLogin
     *
     * @return LdapConnecteur
     */
    public function setUtilisateurLogin(string $utilisateurLogin): LdapConnecteur
    {
        $this->utilisateurLogin = $utilisateurLogin;

        return $this;
    }



    /**
     * @return string
     */
    public function getUtilisateurFiltre(): string
    {
        return $this->utilisateurFiltre;
    }



    /**
     * @param string $utilisateurFiltre
     *
     * @return LdapConnecteur
     */
    public function setUtilisateurFiltre(string $utilisateurFiltre): LdapConnecteur
    {
        $this->utilisateurFiltre = $utilisateurFiltre;

        return $this;
    }



    /**
     * @return string
     */
    public function getUtilisateurCode(): string
    {
        return $this->utilisateurCode;
    }



    /**
     * @param string $utilisateurCode
     *
     * @return LdapConnecteur
     */
    public function setUtilisateurCode(string $utilisateurCode): LdapConnecteur
    {
        $this->utilisateurCode = $utilisateurCode;

        return $this;
    }



    /**
     * @return string
     */
    public function getUtilisateurCodeFiltre(): string
    {
        return $this->utilisateurCodeFiltre;
    }



    /**
     * @param string $utilisateurCodeFiltre
     *
     * @return LdapConnecteur
     */
    public function setUtilisateurCodeFiltre(string $utilisateurCodeFiltre): LdapConnecteur
    {
        $this->utilisateurCodeFiltre = $utilisateurCodeFiltre;

        return $this;
    }



    /**
     * @return string
     */
    public function getUtilisateurExtraMasque(): string
    {
        return $this->utilisateurExtraMasque;
    }



    /**
     * @param string $utilisateurExtraMasque
     *
     * @return LdapConnecteur
     */
    public function setUtilisateurExtraMasque(string $utilisateurExtraMasque): LdapConnecteur
    {
        $this->utilisateurExtraMasque = $utilisateurExtraMasque;

        return $this;
    }



    /**
     * @return array
     */
    public function getUtilisateurExtraAttributes(): array
    {
        return $this->utilisateurExtraAttributes;
    }



    /**
     * @param array $utilisateurExtraAttributes
     *
     * @return LdapConnecteur
     */
    public function setUtilisateurExtraAttributes(array $utilisateurExtraAttributes): LdapConnecteur
    {
        $this->utilisateurExtraAttributes = $utilisateurExtraAttributes;

        return $this;
    }

}
