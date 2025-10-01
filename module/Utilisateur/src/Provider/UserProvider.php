<?php

namespace Utilisateur\Provider;

use Application\Service\ContextService;
use Doctrine\ORM\EntityManager;
use Framework\User\UserInterface;
use Framework\User\UserProfile;
use Framework\User\UserProfileInterface;
use Framework\User\UserProviderInterface;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Authentication\AuthenticationService;
use UnicaenAuthentification\Service\UserContext;
use Utilisateur\Connecteur\LdapConnecteur;
use Utilisateur\Entity\Db\Affectation;

class UserProvider implements UserProviderInterface
{
    public function __construct(
        private readonly EntityManager         $entityManager,
        private readonly UserContext           $userContext,
        private readonly LdapConnecteur        $ldap,
        private readonly AuthenticationService $authenticationService,
        private readonly ContextService        $contextService,
    )
    {

    }



    public function getUser(): ?UserInterface
    {
        $identity = $this->authenticationService->getIdentity();

        return $identity['db'] ?? null;
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

        $annee           = $this->contextService->getAnnee();

        $dql = "
            SELECT
              i, partial s.{id, code}, partial ti.{id,libelle}
            FROM
              ".Intervenant::class." i
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
            $ti = $intervenant->getStatut()->getTypeIntervenant();
            $typesIntervenants[$ti->getId()] = $intervenant;
        }

        foreach( $typesIntervenants as $intervenant ){
            $profiles[] = $intervenant->getProfile();
        }

        return $profiles;
    }



    /**
     * @return array|string[]
     */
    public function getPrivileges(?UserProfileInterface $profile): array
    {
        return [];
    }



    public function onProfileChange(?UserProfile $newProfile)
    {
        $roles = $this->userContext->getSelectableIdentityRoles();
        foreach ($roles as $role) {
            if ($role->getRoleId() == $newProfile->getId()) {
                $this->userContext->setSelectedIdentityRole($role);
            }
        }
    }

}