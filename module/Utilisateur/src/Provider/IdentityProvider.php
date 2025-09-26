<?php

namespace Utilisateur\Provider;

use Application\Service\Traits\ContextServiceAwareTrait;
use BjyAuthorize\Provider\Identity\ProviderInterface as IdentityProviderInterface;
use Intervenant\Service\IntervenantServiceAwareTrait;
use UnicaenApp\HostLocalization\HostLocalizationAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenApp\Traits\SessionContainerTrait;
use UnicaenAuthentification\Provider\Identity\ChainableProvider;
use UnicaenAuthentification\Provider\Identity\ChainEvent;
use Utilisateur\Entity\Db\Affectation;
use Utilisateur\Entity\Db\Role;

/**
 * Classe chargée de fournir les rôles que possède l'identité authentifiée.
 *
 */
class IdentityProvider implements ChainableProvider, IdentityProviderInterface
{
    use EntityManagerAwareTrait;
    use SessionContainerTrait;
    use IntervenantServiceAwareTrait;
    use ContextServiceAwareTrait;
    use HostLocalizationAwareTrait;


    /**
     * {@inheritDoc}
     */
    public function injectIdentityRoles(ChainEvent $event): void
    {
        $event->addRoles($this->getIdentityRoles());
    }



    public function clearIdentityRoles(): void
    {
        $session                = $this->getSessionContainer();
        $session->identityRoles = [];
    }



    /**
     * {@inheritDoc}
     */
    public function getIdentityRoles(): array
    {
        $session = $this->getSessionContainer();

        // pas de cache si on est que guest
        //if (!$session->offsetExists('identityRoles') || empty($session->identityRoles) || count($session->identityRoles) < 2) {
            $filter = $this->getEntityManager()->getFilters()->enable('historique');
            $filter->init([
                              Role::class,
                              Affectation::class,
                          ]);

            $identityRoles = ['guest' => 'guest'];

            $inEtablissement = $this->getHostLocalization()->inEtablissement();

            $this->getServiceContext()->getServiceUserContext()->clearIdentityRoles();

            /**
             * Rôles que possède l'utilisateur dans la base de données.
             */
            if ($utilisateur = $this->getServiceContext()->getUtilisateur()) {
                $identityRoles = ['user' => 'user'];
                $affectations  = $this->getEntityManager()->getRepository(Affectation::class)->findBy(['utilisateur' => $utilisateur]);
                foreach ($affectations as $affectation) {
                    /* @var $affectation Affectation */
                    $role = $affectation->getRole();
                    try {
                        if ($role->estNonHistorise() && ($inEtablissement || $role->isAccessibleExterieur())) {
                            $roleId = $role->getCode();
                            if ($structure = $affectation->getStructure()) {
                                $roleId .= '-' . $structure->getSourceCode();
                            }
                            $identityRoles[] = $roleId;
                        }
                    } catch (\Exception $e) {
                        // on ignore les affectations dont les rôles ont été supprimés
                    }
                }
            }

            /**
             * Rôle lié au statut de l'intervenant
             */
            if ($intervenant = $this->getServiceContext()->getIntervenant()) {
                $identityRoles[$intervenant->getStatut()->getRoleId()] = $intervenant->getStatut()->getRoleId();
            }
            $session->identityRoles = $identityRoles;
        //}

        return $session->identityRoles;
    }
}
