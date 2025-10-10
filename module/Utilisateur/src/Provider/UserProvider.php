<?php

namespace Utilisateur\Provider;

use Application\HostLocalization\HostLocalizationOse;
use Application\Provider\Privileges;
use Application\Service\ContextService;
use Doctrine\ORM\EntityManager;
use Unicaen\Framework\Application\Application;
use Unicaen\Framework\Cache\FilesystemCache;
use Unicaen\Framework\Container\Autowire;
use Unicaen\Framework\User\UserProfile;
use Unicaen\Framework\User\UserProfileInterface;
use Unicaen\Framework\User\UserAdapterInterface;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Entity\Db\Statut;
use Laminas\Authentication\AuthenticationService;
use Utilisateur\Connecteur\LdapConnecteur;
use Utilisateur\Entity\Db\Affectation;
use Utilisateur\Entity\Db\Role;
use Utilisateur\Entity\Db\Utilisateur;

class UserProvider implements UserAdapterInterface
{

    private array $noAdminPrivileges = [
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
        private readonly HostLocalizationOse   $hostLocalization,
        private readonly FilesystemCache       $filesystemCache,

        #[Autowire(config: 'application/privileges')]
        private readonly ?array                $customPrivileges,
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

        $inEtablissement = $this->hostLocalization->inEtablissement();

        /* Recherche des affectations */
        $dql = "
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
        if (!$inEtablissement) {
            $dql .= "AND r.accessibleExterieur = 1";
        }
        $query = $this->entityManager->createQuery($dql);
        $query->enableResultCache(true);
        $query->setResultCacheId('role_profiles_' . $user->getId());

        $query->setParameters(['user' => $user->getId()]);

        /** @var Affectation[] $affectations */
        $affectations = $query->getResult();

        foreach ($affectations as $affectation) {
            $profiles[] = $affectation->getProfile();
        }


        /* Recherche des intervenants */
        $utilisateurCode = $this->ldap->getUtilisateurCourantCode();

        if (!$utilisateurCode) {
            // on en reste là si pas de code trouvé
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
        $query->enableResultCache(true);
        $query->setResultCacheId('intervenant_profiles_' . $user->getId() . '_' . $annee->getId());

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

        usort($profiles, function ($a, $b) {
            return $a->getDisplayName() > $b->getDisplayName() ? 1 : -1;
        });

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
        $role   = $profile?->getContext('role');
        $statut = $profile?->getContext('statut');

        $rolePrivileges   = $this->getRolePrivileges($role);
        $statutPrivileges = $this->getStatutPrivileges($statut);
        $customPrivileges = $this->customPrivileges[$role?->getCode()] ?? [];

        return array_merge($rolePrivileges, $statutPrivileges, $customPrivileges);
    }



    protected function getStatutPrivileges(?Statut $statut)
    {
        if (!$statut) {
            return [];
        }
        return array_keys($statut->getPrivileges());
    }



    protected function getRolePrivileges(?Role $role): array
    {
        if (!$role) {
            return [];
        }
        if (Role::ADMINISTRATEUR === $role->getCode()) {
            return $this->getAvailablePrivileges();
        }

        $privileges = [];

        $sql    = "
        SELECT 
          cp.code || '-' || p.code priv_code
        FROM 
          privilege p 
          JOIN categorie_privilege cp ON cp.id = p.categorie_id
          JOIN role_privilege rp ON rp.privilege_id = p.id
        WHERE
            rp.role_id = :roleId
        ";
        $result = $this->entityManager->getConnection()->executeQuery($sql, ['roleId' => $role->getId()]);
        while ($privilege = $result->fetchOne()) {
            $privileges[] = $privilege;
        }

        return $privileges;
    }



    public function getAvailablePrivileges(): array
    {
        $privileges = [];

        $sql    = "
        SELECT 
          cp.code || '-' || p.code priv_code
        FROM 
          privilege p 
          JOIN categorie_privilege cp ON cp.id = p.categorie_id
          JOIN role_privilege rp ON rp.privilege_id = p.id
        ";
        $result = $this->entityManager->getConnection()->executeQuery($sql);
        while ($privilege = $result->fetchOne()) {
            $privileges[] = $privilege;
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



    public function onClearCache(): void
    {
        $user  = $this->getUser();
        $annee = $this->contextService->getAnnee();

        if ($user) {
            $this->filesystemCache->delete('Doctrine/intervenant_profiles_' . $user->getId() . '_' . $annee->getId());
            $this->filesystemCache->delete('Doctrine/role_profiles_' . $user->getId());

        }
    }
}