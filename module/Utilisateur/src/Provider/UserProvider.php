<?php

namespace Utilisateur\Provider;

use Application\Provider\Privileges;
use Application\Service\ContextService;
use Doctrine\ORM\EntityManager;
use Unicaen\Framework\Application\Application;
use Unicaen\Framework\Container\Autowire;
use Unicaen\Framework\User\UserManager;
use Unicaen\Framework\User\UserProfile;
use Unicaen\Framework\User\UserProfileInterface;
use Unicaen\Framework\User\UserAdapterInterface;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Entity\Db\Statut;
use Laminas\Authentication\AuthenticationService;
use Utilisateur\Connecteur\LdapConnecteur;
use Utilisateur\Entity\Db\Affectation;
use Utilisateur\Entity\Db\Privilege;
use Utilisateur\Entity\Db\Role;
use Utilisateur\Entity\Db\Utilisateur;

class UserProvider implements UserAdapterInterface
{

    private array $noAdminPrivileges     = [
        Privileges::ENSEIGNEMENT_PREVU_AUTOVALIDATION,
        Privileges::ENSEIGNEMENT_REALISE_AUTOVALIDATION,
        Privileges::REFERENTIEL_PREVU_AUTOVALIDATION,
        Privileges::REFERENTIEL_REALISE_AUTOVALIDATION,
    ];


    public function __construct(
        private readonly EntityManager         $entityManager,
        private readonly LdapConnecteur        $ldap,
        private readonly AuthenticationService $authenticationService,
        private readonly ContextService        $contextService,

        #[Autowire(config:'application/privileges')]
        private readonly ?array $customPrivileges,
    )
    {

    }



    public function getUser(): ?Utilisateur
    {
        $identity = $this->authenticationService->getIdentity();

        return $identity['db'] ?? null;
    }



    public function isUsurpationEnabled(): bool
    {
        if ($this->isUsurpationEnCours()) {
            return true;
        }

        $usersAllowed = Application::getInstance()->config()['ldap']['autorisationsUsurpation'] ?? [];
        return in_array(
            $this->getUser()?->getUsername(),
            $usersAllowed);
    }



    public function isUsurpationEnCours(): bool
    {
        $identity = $this->authenticationService->getIdentity();

        return isset($identity['usurpation']['usurpateur']);
    }



    /**
     * @return array|UserProfileInterface[]
     */
    public function getProfiles(): array
    {
        $user = $this->getUser();

        if (!$user) {
            // pas de connexion, pas de profil
            return [];
        }

        $profiles = [];

        /* Recherche des affectations */
        $dql   = "
            SELECT 
              a, r, s
            FROM 
              " . Affectation::class . " a
              JOIN a.role r
              LEFT JOIN a.structure s
            WHERE
              a.histoDestruction IS NULL
              AND a.utilisateur = :user
            ";
        $query = $this->entityManager->createQuery($dql);
        $query->setParameters(['user' => $user->getId()]);

        /** @var Affectation[] $affectations */
        $affectations = $query->getResult();

        foreach ($affectations as $affectation) {
            $profiles[] = $affectation->getProfile();
        }


        /* Recherche des intervenants */
        $utilisateurCode = $this->ldap->getUtilisateurCourantCode();

        if (!$utilisateurCode) {
            // on en reste là si pas de coe trouvé
            return $profiles;
        }

        $annee = $this->contextService->getAnnee();

        $dql   = "
            SELECT
              i, s, ti
            FROM
              " . Intervenant::class . " i
              JOIN i.statut s
              JOIN s.typeIntervenant ti
            WHERE
              i.histoDestruction IS NULL
              AND i.utilisateurCode = :utilisateurCode
              AND i.annee = :annee
        ";
        $query = $this->entityManager->createQuery($dql);
        $query->setParameters(['utilisateurCode' => $utilisateurCode, 'annee' => $annee->getId()]);

        /** @var Intervenant[] $intervenants */
        $intervenants = $query->getResult();

        $typesIntervenants = [];

        foreach ($intervenants as $intervenant) {
            $ti                              = $intervenant->getStatut()->getTypeIntervenant();
            $typesIntervenants[$ti->getId()] = $intervenant;
        }

        foreach ($typesIntervenants as $intervenant) {
            $profiles[] = $intervenant->getProfile();
        }

        return $profiles;
    }



    public function getProfileDefaultId(): null|int|string
    {
        // pas de profil par défaut
        return null;
    }



    /**
     * @return array|string[]
     */
    public function getCurrentPrivileges(?UserProfileInterface $profile): array
    {
        $privileges = [];

        /** @var Statut $statut */
        if ($statut = $profile?->getContext('statut')) {
            /** @var Statut|null $statut */
            $privileges = array_keys($statut->getPrivileges());
        } elseif ($role = $profile?->getContext('role')) {
            /** @var Role|null $role */
            /** @var Privilege[] $ps */
            if (Role::ADMINISTRATEUR === $role->getCode()) {
                $privileges = $this->getAdministrateurPrivileges();
            } else {
                $ps         = $role->getPrivileges();
                $privileges = [];
                foreach ($ps as $privilege) {
                    $privileges[] = $privilege->getFullCode();
                }
                $customPrivileges = $this->customPrivileges[$role->getCode()] ?? [];
                foreach( $customPrivileges as $privilege ){
                    $privileges[] = $privilege;
                }
            }
        }

        return $privileges;
    }



    public function getAvailablePrivileges(): array
    {
        $privileges = [];
        $dql = 'SELECT p, c FROM '.Privilege::class.' p JOIN p.categorie c';
        $query = $this->entityManager->createQuery($dql);

        /** @var Privilege[] $res */
        $res = $query->getResult();
        foreach( $res as $privilege ){
            $privileges[] = $privilege->getFullCode();
        }

        return $privileges;
    }



    protected function getAdministrateurPrivileges(): array
    {
        $rc         = new \ReflectionClass(Privileges::class);
        $privileges = array_values($rc->getConstants());
        foreach ($privileges as $index => $privilege) {
            if (in_array($privilege, $this->noAdminPrivileges)) {
                unset($privileges[$index]);
            }
        }
        return $privileges;
    }



    public function onBeforeProfileChange(): void
    {
        return;
    }



    public function onAfterProfileChange(?UserProfile $newProfile): void
    {
        return;
    }

}